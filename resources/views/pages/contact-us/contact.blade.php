@extends('layouts.landing')

@section('hero')
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>Contacts</h2>
                        <div class="bt-option">
                            <a href="{{ route('welcomepage') }}">Home</a>
                            <span>Details</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Contact Section Begin -->
    <section class="contact-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="contact-text">
                        <h2>Contact Info</h2>
                        <p>
                            For bookings and inquiries, feel free to contact Bellamonte Resort Shogran.
                            Our team is available to assist you with room reservations and travel support.
                        </p>

                        <table>
                            <tbody>
                                <tr>
                                    <td class="c-o">Address:</td>
                                    <td>Bellamonte Resort, Shogran, Khyber Pakhtunkhwa, Pakistan</td>
                                </tr>
                                <tr>
                                    <td class="c-o">Phone:</td>
                                    <td>0329 6777222</td>
                                </tr>
                                <tr>
                                    <td class="c-o">Email:</td>
                                    <td>info@bellamonteresort.com</td>
                                </tr>
                                <tr>
                                    <td class="c-o">Location:</td>
                                    <td>Shogran, Pakistan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-7 offset-lg-1">

                    {{-- ── Success Toast Notification ── --}}
                    @if (session('success'))
                        <div class="bm-toast" id="bmToast">
                            <div class="bm-toast__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <div class="bm-toast__body">
                                <strong>Message Sent!</strong>
                                <span>{{ session('success') }}</span>
                            </div>
                            <div class="bm-toast__progress" id="bmProgress"></div>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}"
                                    class="{{ $errors->has('name') ? 'input-error' : '' }}">
                                @error('name')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <input type="text" name="email" placeholder="Your Email" value="{{ old('email') }}"
                                    class="{{ $errors->has('email') ? 'input-error' : '' }}">
                                @error('email')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-6">
                                <input type="text" name="phone" placeholder="Your Phone (Optional)"
                                    value="{{ old('phone') }}">
                            </div>

                            <div class="col-lg-6">
                                <input type="text" name="subject" placeholder="Subject (Optional)"
                                    value="{{ old('subject') }}">
                            </div>

                            <div class="col-lg-12">
                                <textarea name="message" placeholder="Your Message" class="{{ $errors->has('message') ? 'input-error' : '' }}">{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror

                                <button type="submit">Submit Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="map">
                <iframe src="https://maps.google.com/maps?q=34.640222,73.460861&z=15&output=embed" height="470"
                    width="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->
@endsection

@push('styles')
    <style>
        /* ── Field Errors ── */
        .contact-form input.input-error,
        .contact-form textarea.input-error {
            border-color: #e74c3c !important;
        }

        .field-error {
            color: #e74c3c;
            font-size: 12px;
            display: block;
            margin-top: -10px;
            margin-bottom: 10px;
        }

        /* ── Toast ── */
        .bm-toast {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            background: #1a2e1a;
            border: 1px solid #c9a84c;
            border-radius: 10px;
            padding: 16px 20px 20px;
            margin-bottom: 28px;
            overflow: hidden;
            animation: bmSlideIn 0.4s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        /* Icon circle */
        .bm-toast__icon {
            flex-shrink: 0;
            width: 42px;
            height: 42px;
            background: #c9a84c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1a2e1a;
        }

        /* Text */
        .bm-toast__body {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .bm-toast__body strong {
            color: #c9a84c;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .bm-toast__body span {
            color: #d6d0c4;
            font-size: 13px;
            line-height: 1.5;
        }

        /* Progress bar */
        .bm-toast__progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background: #c9a84c;
            transform-origin: left;
            animation: bmProgress 3s linear forwards;
        }

        /* Fade out */
        .bm-toast.hiding {
            animation: bmSlideOut 0.4s ease forwards;
        }

        /* Keyframes */
        @keyframes bmSlideIn {
            from {
                opacity: 0;
                transform: translateY(-16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bmSlideOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-16px);
            }
        }

        @keyframes bmProgress {
            from {
                transform: scaleX(1);
            }

            to {
                transform: scaleX(0);
            }
        }

        /* Mobile */
        @media (max-width: 576px) {
            .bm-toast {
                padding: 14px 16px 20px;
                gap: 12px;
            }

            .bm-toast__icon {
                width: 36px;
                height: 36px;
            }

            .bm-toast__body strong {
                font-size: 14px;
            }

            .bm-toast__body span {
                font-size: 12px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // 3 second baad toast hide ho jata hai
        (function() {
            var toast = document.getElementById('bmToast');
            if (!toast) return;

            setTimeout(function() {
                toast.classList.add('hiding');
                setTimeout(function() {
                    toast.style.display = 'none';
                }, 400);
            }, 5000);
        })();
    </script>
@endpush
