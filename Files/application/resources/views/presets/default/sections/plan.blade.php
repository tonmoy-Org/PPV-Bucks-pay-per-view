@php
    $planContent = getContent('plan.content', true);
    if (!isset($plans) || $plans->isEmpty()) {
        $plans = App\Models\Plan::where('status', 1)->orderBy('price', 'asc')->get();
    }
    $general = gs();

    $activeUser = auth('advertiser')->user() ?? auth()->user();
    $currentPlanId = $activeUser ? $activeUser->plan_id : null;
@endphp

<!-- ==================== Plan Section Start ==================== -->
<section class="plan-section py-80">
    <div class="container">

        @if($activeUser)
            <!-- Active Plan Status Bar -->
            <div class="alert border-0 rounded-4 shadow-sm mb-4 p-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #f0f9ff; color: #0369a1; border-left: 5px solid #0284c7 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-gem fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark mb-0">
                            @lang('Your Current Active Plan'): 
                            <span class="text-primary fs-6">{{ $activeUser->plan ? $activeUser->plan->name : __('No Active Plan') }}</span>
                            @if($activeUser->plan)
                                <span class="badge bg-success-subtle text-success ms-2">{{ $general->cur_sym }}{{ showAmount($activeUser->plan->price) }}</span>
                            @endif
                        </div>
                        <div class="text-muted small">@lang('All manual/agent deposit payment history & records remain 100% saved in your account.')</div>
                    </div>
                </div>
                <span class="badge bg-primary px-3 py-2 rounded-pill fw-medium">@lang('Select a plan below to upgrade')</span>
            </div>
        @endif

        <!-- Header Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h3 class="fw-bold mb-2">{{ __(@$planContent->data_values->heading ?? 'Choose Your Growth Plan') }}</h3>
                <p class="text-muted fs-6 mx-auto mb-0" style="max-width: 600px;">
                    {{ __(@$planContent->data_values->sub_heading ?? 'Select the best plan tailored to your earning goals and unlock higher rewards.') }}
                </p>
            </div>
        </div>

        <!-- 4 Dynamic Pricing Cards Grid in 1 Row -->
        <div class="row gy-4 justify-content-center align-items-stretch">
            @forelse($plans as $index => $plan)
                @php
                    $isPopular = ($index == 1 || count($plans) == 1);
                    $priceFormatted = showAmount($plan->price);
                    $priceParts = explode('.', (string)$priceFormatted);
                    $mainPrice = $priceParts[0] ?? '0';
                    $cents = isset($priceParts[1]) ? '.' . $priceParts[1] : '';

                    $icons = ['fa-paper-plane', 'fa-rocket', 'fa-crown', 'fa-gem'];
                    $iconClass = $icons[$index % count($icons)];
                    $isCurrentPlan = ($currentPlanId && $currentPlanId == $plan->id);
                @endphp

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="plan-card-pro {{ $isPopular ? 'featured-plan' : '' }}">
                        @if($isCurrentPlan)
                            <span class="popular-badge bg-success text-white" style="background: #16a34a !important;">
                                <i class="fas fa-check-circle me-1"></i> @lang('CURRENT PLAN')
                            </span>
                        @elseif($isPopular)
                            <span class="popular-badge">
                                <i class="fas fa-fire me-1"></i> @lang('MOST POPULAR')
                            </span>
                        @endif

                        <div>
                            <div class="text-center">
                                <div class="plan-icon-wrapper">
                                    <i class="fas {{ $iconClass }}"></i>
                                </div>
                                <h5 class="fw-bold mb-1 text-uppercase text-dark" style="letter-spacing: 0.5px;">
                                    {{ __($plan->name) }}
                                </h5>
                                <span class="text-muted small fw-medium">{{ __($plan->subtitle ?: 'Instant Activation Package') }}</span>

                                <div class="price-box">
                                    <span class="price-currency">{{ $general->cur_sym }}</span>
                                    <span class="price-amount">{{ $mainPrice }}</span>
                                    @if($cents)
                                        <span class="price-cents">{{ $cents }}</span>
                                    @endif
                                </div>
                                <span class="price-period">{{ __($plan->validity_text ?: 'One-time payment • Lifetime validity') }}</span>
                            </div>

                            @php
                                $customFeatures = method_exists($plan, 'getFeaturesList') ? $plan->getFeaturesList() : [];
                            @endphp

                            <ul class="plan-features-list">
                                @if(!empty($customFeatures))
                                    @foreach($customFeatures as $featureItem)
                                        @php
                                            $isExcluded = false;
                                            $cleanText = $featureItem;
                                            if (preg_match('/^(?:-|x|X|!|cross:)\s*(.*)$/u', $featureItem, $matches)) {
                                                $isExcluded = true;
                                                $cleanText = $matches[1];
                                            }
                                        @endphp
                                        <li>
                                            @if($isExcluded)
                                                <div class="feature-cross-icon"><i class="fas fa-times"></i></div>
                                                <span class="text-muted opacity-75">{{ __($cleanText) }}</span>
                                            @else
                                                <div class="feature-check-icon"><i class="fas fa-check"></i></div>
                                                <span>{{ __($cleanText) }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                @else
                                    <li>
                                        <div class="feature-check-icon"><i class="fas fa-check"></i></div>
                                        <span><strong>{{ $plan->point }}</strong> @lang('Ad Creation Points Included')</span>
                                    </li>
                                    <li>
                                        <div class="feature-check-icon"><i class="fas fa-check"></i></div>
                                        <span>@lang('Instant Point Crediting to Account')</span>
                                    </li>
                                    <li>
                                        <div class="feature-check-icon"><i class="fas fa-check"></i></div>
                                        <span>@lang('Full Access to Ad Campaign Builder')</span>
                                    </li>
                                    <li>
                                        <div class="feature-check-icon"><i class="fas fa-check"></i></div>
                                        <span>@lang('Express Ad Approval') @if($isPopular) <span class="badge bg-success-subtle text-success ms-1">@lang('Priority')</span> @endif</span>
                                    </li>
                                    <li>
                                        <div class="feature-check-icon"><i class="fas fa-check"></i></div>
                                        <span>@lang('24/7 Dedicated Support Assistance')</span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="plan-card-footer mt-3">
                            @if($isCurrentPlan)
                                <button class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm" disabled style="background: #16a34a !important; border-color: #16a34a !important;">
                                    <i class="fas fa-check-circle me-1"></i> @lang('Current Plan')
                                </button>
                            @else
                                @if(auth('advertiser')->check())
                                    <a href="{{ route('advertiser.payment', $plan->id) }}" class="btn btn--base btn-plan-action shadow-sm">
                                        @lang('Upgrade Plan') <i class="fas fa-arrow-right"></i>
                                    </a>
                                @elseif(auth()->check())
                                    <a href="{{ route('plan.payment', $plan->id) }}" class="btn btn--base btn-plan-action shadow-sm">
                                        @lang('Upgrade Plan') <i class="fas fa-arrow-right"></i>
                                    </a>
                                @else
                                    <a href="{{ route('user.login') }}" class="btn btn--base btn-plan-action shadow-sm">
                                        @lang('Upgrade Plan') <i class="fas fa-arrow-right"></i>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="empty-plan p-5 rounded-4 bg-white border border-dashed shadow-sm mx-auto" style="max-width: 500px;">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="fw-bold text-dark">@lang('No Membership Plans Available')</h5>
                        <p class="text-muted mb-0">@lang('Plans are currently being updated. Please check back shortly.')</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</section>
<!-- ==================== Plan Section End ==================== -->
