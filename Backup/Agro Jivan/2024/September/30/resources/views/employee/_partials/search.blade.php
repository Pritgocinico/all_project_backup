<div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
    <div class="card-title">
        <div class="d-flex align-items-center position-relative my-1">
            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
            <input type="text" data-kt-user-table-filter="search" id="search_data" class="form-control w-250px ps-13"
                onkeyup="employeeOrderAjaxList(1)" placeholder="Search order" />
        </div>
    </div>

    <div class="card-toolbar">
        <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
            <div class="parent-filter-menu">
                <button type="button" class="btn btn-light-primary me-3 order_filter_option">
                    <i class="ki-outline ki-filter fs-2"></i> Filter
                </button>
                <div class="menu filter-menu w-300px w-md-325px" data-kt-menu="true">
                    <div class="px-7 py-5">
                        <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                    </div>
                    <div class="separator border-gray-200"></div>
                    <div class="px-7 py-5" data-kt-user-table-filter="form">
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">District:</label>
                            <select class="form-select form-select-solid fw-bold"
                                id="order_district" data-placeholder="Select option" onchange="getSubDistrictDetail()"
                                data-allow-clear="true" data-kt-user-table-filter="two-step"
                                data-hide-search="true">
                                <option value="">Select District</option>
                                @foreach ($orderDistricts as $distrcit)
                                    @if (isset($distrcit->districtDetail))
                                        <option value="{{ $distrcit->district }}">
                                            {{ $distrcit->districtDetail->district_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Sub District:</label>
                            <select class="form-select form-select-solid fw-bold"
                                id="order_sub_district" data-placeholder="Select option"
                                data-allow-clear="true" data-kt-user-table-filter="two-step"
                                data-hide-search="true">
                                <option value="">Select Sub District</option>
                                @foreach ($orderSubDistricts as $subDistricts)
                                    @if(isset($subDistricts->subDistrictDetail))
                                        <option value="{{$subDistricts->sub_district}}">{{$subDistricts->subDistrictDetail->sub_district_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @if ($type == '')
                        <div class="mb-10">
                            <label class="form-label fs-6 fw-semibold">Status:</label>
                            <select class="form-select form-select-solid fw-bold" id="order_status"
                                data-placeholder="Select option" data-allow-clear="true"
                                data-kt-user-table-filter="two-step" data-hide-search="true">
                                <option value="">Select Status</option>
                                <option value="1">Pending Order</option>
                                <option value="2">Confirmed </option>
                                <option value="3">On Delivery </option>
                                <option value="4">Cancel Order </option>
                                <option value="5">Returned </option>
                                <option value="6">Completed</option>
                            </select>
                        </div>
                    @endif
                    <div class="mb-10">
                        <label class="form-label fs-6 fw-semibold">Date:</label>
                        <input type="text" placeholder="Select Date" class="form-control search_order_date"
                            id="order_date" name="order_date" value="">
                    </div>

                        <div class="d-flex justify-content-end">
                            <button type="reset"
                                class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                data-kt-menu-dismiss="true" onclick="resetSearch()"
                                data-kt-user-table-filter="reset">Reset</button>
                            <button type="submit"
                                class="btn btn-primary fw-semibold px-6 order_filter_option"
                                data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                onclick="employeeOrderAjaxList(1)">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">

                <a href="{{ route('employee-orders.create') }}" class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                    <i class="ki-outline ki-plus fs-2"></i> New Order
                </a>
            </div>

        </div>
    </div>
</div>
