<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\CustomerFeedback;
use App\Models\EmailTemplate;
use App\Models\MapReport;
use App\Models\ScheduleReport as ModelsScheduleReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Mpdf\Mpdf;
use Mail;

class ScheduleReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schedule = ModelsScheduleReport::where('status', '1')->first();
        $emailSent = 0;
        $now = Carbon::now();
        $currentDate = Carbon::parse($schedule->schedule_date);
        if ($schedule) {
            if ($schedule->schedule == "Daily") {
                if ($schedule->timeframe_cover_type == "months") {
                    $futureDate = $currentDate->copy()->addMonths($schedule->timeframe_cover_date);
                    if ($now < $futureDate) {
                        $emailSent = 1;
                    }
                } else if ($schedule->timeframe_cover_type == "days") {
                    $futureDate = $currentDate->copy()->addDays($schedule->timeframe_cover_date);
                    if ($now < $futureDate) {
                        $emailSent = 1;
                    }
                } else {
                    $futureDate = $currentDate->copy()->addWeeks($schedule->timeframe_cover_date);
                    if ($now < $futureDate) {
                        $emailSent = 1;
                    }
                }
            } else {
                if ($schedule->frequency == "Daily") {
                    if ($schedule->timeframe_cover_type == "months") {
                        $futureDate = $currentDate->copy()->addMonths($schedule->timeframe_cover_date);
                        if ($now < $futureDate) {
                            $emailSent = 1;
                        }
                    } else if ($schedule->timeframe_cover_type == "days") {

                        $futureDate = $currentDate->copy()->addDays($schedule->timeframe_cover_date);
                        if ($now < $futureDate) {
                            $emailSent = 1;
                        }
                    } else {
                        $futureDate = $currentDate->copy()->addWeeks($schedule->timeframe_cover_date);
                        if ($now < $futureDate) {
                            $emailSent = 1;
                        }
                    }
                } else if ($schedule->frequency == "Yearly") {
                    if ($schedule->timeframe_cover_type == "months") {
                        $futureDate = $currentDate->copy()->addMonths($schedule->timeframe_cover_date);
                        if ($now < $futureDate) {
                            $emailSent = 1;
                        }
                    } else if ($schedule->timeframe_cover_type == "days") {

                        $futureDate = $currentDate->copy()->addDays($schedule->timeframe_cover_date);
                        if ($now < $futureDate) {
                            $emailSent = 1;
                        }
                    } else {
                        $futureDate = $currentDate->copy()->addWeeks($schedule->timeframe_cover_date);
                        if ($now < $futureDate) {
                            $emailSent = 1;
                        }
                    }
                } else if ($schedule->frequency == "Weekly") {
                    $weekDay = explode(",", $schedule->weekly_interval);
                    if (in_array($now->format('l'), $weekDay)) {
                        if ($schedule->timeframe_cover_type == "months") {
                            $futureDate = $currentDate->copy()->addMonths($schedule->timeframe_cover_date);
                            if ($now < $futureDate) {
                                $emailSent = 1;
                            }
                        } else if ($schedule->timeframe_cover_type == "days") {
                            $futureDate = $currentDate->copy()->addDays($schedule->timeframe_cover_date);
                            if ($now < $futureDate) {
                                $emailSent = 1;
                            }
                        } else {
                            $futureDate = $currentDate->copy()->addWeeks($schedule->timeframe_cover_date);
                            if ($now < $futureDate) {
                                $emailSent = 1;
                            }
                        }
                    }
                } else {
                    $monthDay = explode(",", $schedule->monthly_date);
                    if (in_array($now->format('d'), $monthDay)) {
                        if ($schedule->timeframe_cover_type == "months") {
                            $futureDate = $currentDate->copy()->addMonths($schedule->timeframe_cover_date);
                            if ($now < $futureDate) {
                                $emailSent = 1;
                            }
                        } else if ($schedule->timeframe_cover_type == "days") {
                            $futureDate = $currentDate->copy()->addDays($schedule->timeframe_cover_date);
                            if ($now < $futureDate) {
                                $emailSent = 1;
                            }
                        } else {
                            $futureDate = $currentDate->copy()->addWeeks($schedule->timeframe_cover_date);
                            if ($now < $futureDate) {
                                $emailSent = 1;
                            }
                        }
                    }
                }
            }
        }
        $business = Business::where('id', $schedule->business_id)->first();
        if ($emailSent == 1 && isset($business)) {
            $mapReport = MapReport::where('business_id', $schedule->business_id)->first();
            $response1 = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id'  => $business->place_id,
                'key'       => $business->api_key,
            ]);
            $jsonData1 = json_decode($response1->getBody(), true);
            $message = '';
            $data = json_decode($mapReport->details);
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
            $body = str_replace("[[user_name]]", $reportTitle, $body);
            $body = str_replace("[[report_title]]", $reportTitle, $body);
            $body = str_replace("[[report_date]]", $reportDate, $body);
            $body = str_replace("[[report_url]]", $pdfPath, $body);
            $body = str_replace("[[brand_name]]", $brandName, $body);
            $emailString = $schedule->add_recipient;
            if(stripos($emailString, ",") !== false){
                $emailArray = explode(",", $emailString);
            } else {
                $emailArray = [$schedule->add_recipient];
            }
            foreach($emailArray as$email){
                Mail::send([], [], function ($message) use ($email, $subject, $pdfPath,$body) {
                    $message->from('reviews@reviewmgr.com', 'FWD Reviews')
                        ->to($email)
                        ->text(strip_tags($body))
                        ->subject($subject)
                        ->attach($pdfPath);
                });
            }
        }
        info('Report Mail Sent');
    }
}
