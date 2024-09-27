<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Business;
use App\Models\Setting;
use App\Models\UserRecipient;
use App\Models\UserEmailSetting;
use Mail;
use App\Mail\SendMailable;
use Carbon\Carbon;
use App\Models\CustomerFeedback;
use App\Models\EmailTemplate;
use App\Models\MapReport;
use App\Models\ScheduleReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use jdavidbakr\MailTracker\Model\SentEmail;
use Mpdf\Mpdf;
use PDO;

class ReportController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }

    public function analyticReport(Request $request)
    {
        $startingDate = $request->date_from;
        $endDate = $request->date_to;
        if ($startingDate == "" && $endDate == "") {
            $endDate = Carbon::now();
            $startingDate = Carbon::now();
            $startDate = Carbon::now()->subDays(30);
        } else {
            $startDate = Carbon::parse($startingDate);
            $startingDate = Carbon::parse($startingDate);
            $endDate = Carbon::parse($endDate);
        }
        $page = 'Analytics';
        $icon = 'dashboard.png';
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('status', 1)->first();
        }

        $monthWiseReviews = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthWiseReviews[$month] = 0;
        }
        $response1 = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id'  => $business->place_id,
            'key'       => $business->api_key,
        ]);
        $jsonData1 = json_decode($response1->getBody(), true);
        $message = '';
        if (array_key_exists('result', $jsonData1)) {
            $data = $jsonData1['result']['reviews'];
        } else {
            $data = [];
            $message = $jsonData1['error_message'];
        }
        $filteredReviews = array_filter($data, function ($data) use ($startDate, $endDate) {
            $reviewDate = Carbon::createFromTimestamp($data['time']);
            return ($reviewDate >= $startDate && $reviewDate <= $endDate);
        });
        $feedbacks = CustomerFeedback::where('user_id', Auth::user()->business)->where('status', 1)->get();

        $recentTimeFrame = strtotime('-4 months');
        $recentReviewCount = 0;
        foreach ($data as $review) {
            $createdAt = strtotime($review['time']);
            if ($createdAt >= $recentTimeFrame) {
                $recentReviewCount++;
            }
        }
        while ($startDate->lessThanOrEqualTo($endDate)) {
            $labels[] = $startDate->format('M Y');
            $reviewsInCurrentMonth = 0;
            $reviewsInCurrentMonth = array_filter($data, function ($review) use ($startDate, $endDate) {
                $reviewDate = Carbon::createFromTimestamp($review['time']);
                return ($reviewDate >= $startDate && $reviewDate <= $endDate);
            });
            $customerFeedback[] = CustomerFeedback::whereMonth('created_at', $startDate->format('m'))->whereYear('created_at', $startDate->format('Y'))->count();
            $campaign[] = SentEmail::whereMonth('created_at', $startDate->format('m'))->whereYear('created_at', $startDate->format('Y'))->count();
            $clickThrough[] = UserEmailSetting::whereMonth('created_at', $startDate->format('m'))->whereYear('created_at', $startDate->format('Y'))->count();
            $startDate->addMonth();
            $reviewCounts[] = count($reviewsInCurrentMonth);
        }
        return view('client.analytic_report.analytic', compact('customerFeedback', 'clickThrough', 'campaign', 'labels', 'reviewCounts', 'startingDate', 'startDate', 'endDate', 'page', 'icon', 'business', 'feedbacks', 'data', 'filteredReviews', 'recentReviewCount'));
    }

    public function generatedReport(Request $request)
    {
        $page = 'Generated Reports';
        $icon = 'dashboard.png';
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('status', 1)->first();
        }
        $reports = MapReport::orderBy('id', 'desc')->get();
        return view('client.generated_report.generated', compact('page', 'icon', 'business', 'reports'));
    }
    public function generatedReportStore(Request $request)
    {
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('status', 1)->first();
        }
        $reviews = $this->getAllReviews($business);
        $startDate = $request->date_start;
        $endDate = $request->date_end;
        $filteredReviews = array_filter($reviews, function ($data) use ($startDate, $endDate) {
            $reviewDate = Carbon::createFromTimestamp($data['time']);
            return ($reviewDate >= $startDate && $reviewDate <= $endDate);
        });
        $reportData = [
            'user_id' => $business->client_id,
            'business_id' => $business->id,
            'place_id' => $business->place_id,
            'report_type' => $business->name,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'name' => $request->report_title,
            'details' => json_encode($filteredReviews),
            'export_csv' => $request->csv_report == "on" ? '1' : '0',
            'export_pdf' => $request->pdf_report == "on" ? '1' : '0',
            'language' => 'en',
        ];
        $report = MapReport::create($reportData);
        if ($report) {
            return redirect()->back()->with('success', 'Report generated successfully.');
        }
        return redirect()->back()->with('error', 'Something Went To Wrong!.');
    }

    public function getAllReviews($business)
    {
        $response1 = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id'  => $business->place_id,
            'key'       => $business->api_key,
        ]);
        $jsonData1 = json_decode($response1->getBody(), true);
        if (array_key_exists('result', $jsonData1)) {
            $reports = $jsonData1['result']['reviews'];
        } else {
            $reports = [];
        }
        return $reports;
    }

    public function exportReport(Request $request, $id)
    {
        $page = 'Generated Reports';
        $icon = 'dashboard.png';
        $type = $request->type;
        $mapReport = MapReport::find($id);
        $startDate = Carbon::parse($mapReport->start_date);
        $startingDate = Carbon::parse($mapReport->start_date);
        $endDate = Carbon::parse($mapReport->end_date);
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('status', 1)->first();
        }
        $monthWiseReviews = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthWiseReviews[$month] = 0;
        }
        $response1 = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id'  => $business->place_id,
            'key'       => $business->api_key,
        ]);
        // dd($response1);
        $jsonData1 = json_decode($response1->getBody(), true);
        $message = '';
        $data = json_decode($mapReport->details);
        $filteredReviews = count((array)$data);
        $feedbacks = CustomerFeedback::where('user_id', Auth::user()->business)->where('status', 1)->get();
        $recentTimeFrame = strtotime('-4 months');
        $recentReviewCount = 0;
        foreach ($data as $review) {
            $createdAt = strtotime($review->time);
            if ($createdAt >= $recentTimeFrame) {
                $recentReviewCount++;
            }
        }
        while ($startDate->lessThanOrEqualTo($endDate)) {
            $labels[] = $startDate->format('M Y');
            $reviewsInCurrentMonth = 0;
            $reviewsInCurrentMonth = array_filter((array)$data, function ($review) use ($startDate, $endDate) {
                $reviewDate = Carbon::createFromTimestamp($review->time);
                return ($reviewDate >= $startDate && $reviewDate <= $endDate);
            });
            $startDate->addMonth();
            $reviewCounts[] = count($reviewsInCurrentMonth);
        }
        if ($type == "pdf") {
            $html = view('admin.pdf.generated_report', compact('page', 'icon', 'labels', 'reviewCounts', 'business', 'feedbacks', 'recentReviewCount', 'filteredReviews'))->render();

            // Create an instance of mPDF
            $mpdf = new Mpdf();

            // Write HTML content to the PDF
            $mpdf->WriteHTML($html);

            // Output a PDF file directly to the browser for download
            return response($mpdf->Output('sample.pdf', 'D'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="sample.pdf"'
            ]);
        }
        if ($type == "csv") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Report.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Author Name', 'Relative Time Description', 'Text', 'Rating', 'Created At');
            $callback = function () use ($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($data as $order) {
                    $date = date('Y/m/d', $order->time);
                    fputcsv($file, array($order->author_name, $order->relative_time_description, $order->text, $order->rating, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
    }

    public function scheduleReport(Request $request)
    {
        $page = 'Schedule Reports';
        $icon = 'dashboard.png';
        $business = Business::where('id', Auth::user()->business)->first();
        $reports = MapReport::orderBy('id', 'desc')->get();
        $schedule = ScheduleReport::where('status', '1')->first();
        $emailTemplate = EmailTemplate::where('id', 1)->first();
        return view('client.schedule_report.schedule', compact('schedule','page', 'icon', 'business', 'reports', 'emailTemplate'));
    }

    public function updateEmailTemplate(Request $request)
    {
        $emailTemplate = EmailTemplate::where('id', 1)->first();
        $data = [
            'send_weekend' => $request->send_weekend,
            'from_name_mail' => $request->from_name_mail,
            'reply_to' => $request->reply_to,
            'email_subject' => $request->email_subject,
            'email_description' => $request->email_description,
        ];
        if ($emailTemplate) {
            $emailTemplate->update($data);
            return redirect()->back()->with('success', 'Email template updated successfully.');
        }
        $emailTemplate = EmailTemplate::create($data);
        return redirect()->back()->with('success', 'Email template Created successfully.');
    }

    public function storeSchedule(Request $request)
    {
        $schedule = ScheduleReport::where('status', '1')->first();
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('status', 1)->first();
        }
        $scheduleData = "";
        if(isset($request->report_schedule)){
            $scheduleData = implode(",", $request->report_schedule);
        }
        $data = [
            'business_id' => $business->id,
            'frequency' => $request->frequency,
            'daily_interval' => $request->daily_interval,
            'weekly_interval' => $request->weekly_interval,
            'monthly_date' => $request->monthly_date,
            'schedule' => $request->schedule,
            'timeframe_cover_date' => $request->timeframe_cover_date,
            'timeframe_cover_type' => $request->timeframe_cover_type,
            'report_schedule' => $scheduleData,
            'add_recipient' => implode(",", $request->add_recipient),
            'schedule_date' => date('Y-m-d'),
        ];
        if ($schedule) {
            $schedule->update($data);
            return redirect()->back()->with('success', 'Schedule updated successfully.');
        }
        $schedule = ScheduleReport::create($data);
        return redirect()->back()->with('success', 'Schedule created successfully.');
    }

    public function sentTestMail(Request $request)
    {
        if (Auth::user()->business != 0) {
            $business = Business::where('id', Auth::user()->business)->first();
        } else {
            $business = Business::where('client_id', Auth::user()->id)->where('status', 1)->first();
        }
        $mapReport = MapReport::where('business_id', $business->id)->first();
        $response1 = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id'  => $business->place_id,
            'key'       => $business->api_key,
        ]);
        $jsonData1 = json_decode($response1->getBody(), true);
        $message = '';
        $data = array();
        if(isset($mapReports)){
            $data = json_decode($mapReport->details);
        }
        $filteredReviews = count((array)$data);
        $feedbacks = CustomerFeedback::where('user_id', $business->id)->where('status', 1)->get();
        $recentTimeFrame = strtotime('-4 months');
        $recentReviewCount = 0;
        foreach ($data as $review) {
            $createdAt = strtotime($review->time);
            if ($createdAt >= $recentTimeFrame) {
                $recentReviewCount++;
            }
        }
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();
        while ($startDate->lessThanOrEqualTo($endDate)) {
            $labels[] = $startDate->format('M Y');
            $reviewsInCurrentMonth = 0;
            $reviewsInCurrentMonth = array_filter((array)$data, function ($review) use ($startDate, $endDate) {
                $reviewDate = Carbon::createFromTimestamp($review->time);
                return ($reviewDate >= $startDate && $reviewDate <= $endDate);
            });
            $startDate->addMonth();
            $reviewCounts[] = count($reviewsInCurrentMonth);
        }
        $mPdf = new Mpdf();
        $invoiceHtml = view('admin.pdf.generated_report', compact('labels', 'reviewCounts', 'business', 'feedbacks', 'recentReviewCount', 'filteredReviews'))->render();
        $mPdf->WriteHTML($invoiceHtml);
        $pdfDirectory = storage_path('app/public/pdf');
        $pdfFileName = time() . '.pdf';
        $pdfPath = $pdfDirectory . '/' . $pdfFileName;
        $mPdf->Output($pdfPath, 'F');
        $template = EmailTemplate::where('id', '1')->first();
        $email = "test@yopmail.com";
        $subject = $template->email_subject;
        $body = $template->email_description;
        $reportTitle = $business->name;
        $reportDate = date('Y-m-d'); // Example date
        $brandName = "FWD Reviews";
        $body = str_replace("[[report_title]]", $reportTitle, $body);
        $body = str_replace("[[report_date]]", $reportDate, $body);
        $body = str_replace("[[report_url]]", $pdfPath, $body);
        $body = str_replace("[[brand_name]]", $brandName, $body);
        $name = $request->first_name ." " .$request->last_name;
        $body = str_replace("[[user_name]]", $name, $body);
        $email = $request->email_address;
        Mail::send([], [], function ($message) use ($email, $subject, $pdfPath, $body) {
            $message->from('reviews@reviewmgr.com', 'FWD Reviews')
                ->to($email)
                ->text(strip_tags($body))
                ->subject($subject)
                ->attach($pdfPath);
        });
    }
    function scheduleReportStop(Request $request){
        $schedule = ScheduleReport::where('status', '1')->first();
        if($schedule){
            $schedule->update(['status' => '0']);
            return redirect()->back()->with('success', 'Schedule stopped successfully.');
        }else{
            return redirect()->back()->with('error', 'Schedule not found.');
        }
    }
}
