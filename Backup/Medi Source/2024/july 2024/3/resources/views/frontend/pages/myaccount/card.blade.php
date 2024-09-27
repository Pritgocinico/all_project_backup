@extends('frontend.layouts.app')

@section('content')
    <section class="banner-section prdct-parent about-parent position-relative py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                        My Account
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="inner-ac">
                <div class="tab-btn pe-xl-5 pe-lg-3">
                    <ul class="pro-tab">
                        <a href="{{ route('myaccount') }}">
                            <div class="d-flex align-items-center  justify-content-between active">
                                <p class="mb-0">Profile</p>
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </div>
                        </a>
                        <a href="{{ route('orders') }}" class="active">
                            <div class="d-flex align-items-center  justify-content-between ">
                                <p class="mb-0">Orders</p>
                                <i class="fas fa-shopping-basket"></i>
                            </div>
                        </a>
                        <a href="{{ route('card-detail') }}" class="active">
                            <div class="d-flex align-items-center  justify-content-between ">
                                <p class="mb-0">Card Detail</p>
                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                            </div>
                        </a>
                    </ul>
                </div>
                <div class="page-content tab-content">
                    <div>
                        <div class="container">
                            <table class="rwd-table">
                                <tbody>
                                    <tr>
                                        <th>No</th>
                                        <th>Card Name</th>
                                        <th>Card Number</th>
                                        <th>Expire Month</th>
                                        <th>Expire Year</th>
                                        <th>CVV Number</th>
                                        <th>Action</th>
                                    </tr>
                                    @forelse ($cardDetail as $card)
                                        <tr>
                                            <td data-th="No.">
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td data-th="Card Name">
                                                {{ $card->card_name }}
                                            </td>
                                            <td data-th="Card Number">
                                                {{ $card->card_number }}
                                            </td>
                                            <td data-th="Expire Month">
                                                {{ $card->expire_month }}
                                            </td>
                                             <td data-th="Expire Year">
                                                {{ $card->expire_year }}
                                            </td>
                                            <td data-th="CVV Number">
                                                {{ $card->cvv_number }}
                                            </td>
                                            <td data-th="Action">
                                                @if(Auth()->user()->card_id !== $card->id)
                                                    <a href="{{route('default-card',$card->id)}}" class="btn btn-primary btn-sm">Make Default</a>
                                                @else
                                                    <span class="update header-btn border-0 btn-sm">Default Card</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="6">No Data available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    </section>
@endsection
