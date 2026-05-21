<!-- Footer Section Begin -->
<footer class="bm-footer">

    {{-- ── Decorative top border ── --}}
    <div class="bm-footer__topline">
        <span></span><span></span><span></span>
    </div>

    <div class="container">

        {{-- ── Main Footer Grid ── --}}
        <div class="bm-footer__grid">

            {{-- Col 1: Brand ──────────────────────────── --}}
            <div class="bm-footer__brand">
                <a href="{{ url('/') }}" class="bm-footer__logo">
                    <img src="{{ asset('admin/assets/images/bella.png') }}" alt="Bellamonte Resort Logo"
                        style="height:64px; width:auto;">
                    <div class="bm-footer__logo-text">
                        <span class="bm-footer__logo-name">Bellamonte</span>
                        <span class="bm-footer__logo-sub">Resort &bull; Shogran</span>
                    </div>
                </a>

                <p class="bm-footer__tagline">
                    Nestled in the heart of the Kaghan Valley, Bellamonte Resort offers a sanctuary
                    of luxury amidst the majestic peaks of Shogran.
                </p>

                {{-- Social Icons --}}
                <div class="bm-footer__social">
                    @if (\App\Models\Setting::get('facebook'))
                        <a href="{{ \App\Models\Setting::get('facebook') }}" class="bm-footer__social-link"
                            title="Facebook" target="_blank">
                            <i class="fa fa-facebook"></i>
                        </a>
                    @endif

                    @if (\App\Models\Setting::get('twitter'))
                        <a href="{{ \App\Models\Setting::get('twitter') }}" class="bm-footer__social-link"
                            title="Twitter" target="_blank">
                            <i class="fa fa-twitter"></i>
                        </a>
                    @endif

                    @if (\App\Models\Setting::get('instagram'))
                        <a href="{{ \App\Models\Setting::get('instagram') }}" class="bm-footer__social-link"
                            title="Instagram" target="_blank">
                            <i class="fa fa-instagram"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Col 2: Quick Links ─────────────────────── --}}
            <div class="bm-footer__col">
                <h6 class="bm-footer__heading">
                    <span>Quick Links</span>
                </h6>
                <ul class="bm-footer__links">
                    <li><a href="{{ route('rooms.list') }}">Our Rooms</a></li>
                    <li><a href="{{ route('about.us') }}">About Us</a></li>
                    <li><a href="{{ route('events.list') }}">Events</a></li>
                    <li><a href="{{ route('contact.us') }}">Contact</a></li>
                </ul>
            </div>

            {{-- Col 3: Contact Info ────────────────────── --}}
            <div class="bm-footer__col">
                <h6 class="bm-footer__heading">
                    <span>Get In Touch</span>
                </h6>
                <ul class="bm-footer__contact">
                    <li>
                        <span class="bm-footer__contact-icon"><i class="fa fa-phone"></i></span>
                        <span>{{ \App\Models\Setting::get('hotel_phone') ?? '0329 6777222' }}</span>
                    </li>
                    <li>
                        <span class="bm-footer__contact-icon"><i class="fa fa-envelope"></i></span>
                        <span>{{ \App\Models\Setting::get('hotel_email') ?? 'info@bellamonteresort.com' }}</span>
                    </li>
                    <li>
                        <span class="bm-footer__contact-icon"><i class="fa fa-map-marker"></i></span>
                        <span>{{ \App\Models\Setting::get('hotel_address') ?? 'Bellamonte Resort, Shogran, Pakistan' }}</span>
                    </li>
                </ul>
            </div>

        </div>

        {{-- ── Divider ── --}}
        <div class="bm-footer__divider">
            <span></span>
        </div>

        {{-- ── Copyright ── --}}
        <div class="bm-footer__bottom">
            <p>&copy;
                <script>
                    document.write(new Date().getFullYear())
                </script>
                Bellamonte Resort, Shogran. All rights reserved.
            </p>
        </div>

    </div>
</footer>
<!-- Footer Section End -->

<style>
    /* ════════════════════════════════════════
       BELLAMONTE FOOTER — Light Theme
       Matches services-section style
    ════════════════════════════════════════ */

    .bm-footer {
        background: #f9f9f9;
        color: #888;
        padding-top: 0;
        font-family: 'Georgia', serif;
        position: relative;
    }

    /* ── Top decorative line ── */
    .bm-footer__topline {
        display: flex;
        height: 4px;
        width: 100%;
    }

    .bm-footer__topline span:nth-child(1) {
        flex: 2;
        background: #e8e0d8;
    }

    .bm-footer__topline span:nth-child(2) {
        flex: 1;
        background: #dfa974;
    }

    .bm-footer__topline span:nth-child(3) {
        flex: 2;
        background: #e8e0d8;
    }

    /* ── Grid ── */
    .bm-footer__grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr 1.2fr;
        gap: 60px;
        padding: 64px 0 48px;
    }

    /* ── Brand / Logo ── */
    .bm-footer__logo {
        display: flex;
        align-items: center;
        gap: 14px;
        text-decoration: none;
        margin-bottom: 20px;
    }

    .bm-footer__logo-text {
        display: flex;
        flex-direction: column;
    }

    .bm-footer__logo-name {
        font-size: 22px;
        font-weight: 700;
        color: #19191a;
        letter-spacing: 1px;
        line-height: 1.2;
    }

    .bm-footer__logo-sub {
        font-size: 11px;
        color: #dfa974;
        letter-spacing: 3px;
        text-transform: uppercase;
        font-family: Arial, sans-serif;
    }

    .bm-footer__tagline {
        font-size: 13.5px;
        line-height: 1.8;
        color: #999;
        margin-bottom: 24px;
        max-width: 320px;
    }

    /* ── Social Icons ── */
    .bm-footer__social {
        display: flex;
        gap: 10px;
    }

    .bm-footer__social-link {
        width: 38px;
        height: 38px;
        border: 1px solid #e0d8d0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #bbb;
        font-size: 14px;
        text-decoration: none;
        background: #fff;
        transition: all 0.3s ease;
    }

    .bm-footer__social-link:hover {
        background: #dfa974;
        border-color: #dfa974;
        color: #fff;
        transform: translateY(-3px);
    }

    /* ── Column Headings ── */
    .bm-footer__heading {
        font-size: 11px;
        letter-spacing: 3.5px;
        text-transform: uppercase;
        color: #dfa974;
        font-family: Arial, sans-serif;
        font-weight: 700;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e8e0d8;
        position: relative;
    }

    .bm-footer__heading::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 30px;
        height: 1px;
        background: #dfa974;
    }

    /* ── Quick Links ── */
    .bm-footer__links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .bm-footer__links li a {
        color: #999;
        text-decoration: none;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: color 0.25s, gap 0.25s;
    }

    .bm-footer__links li a::before {
        content: '—';
        color: #dfa974;
        font-size: 11px;
    }

    .bm-footer__links li a:hover {
        color: #dfa974;
        gap: 12px;
    }

    /* ── Contact Info ── */
    .bm-footer__contact {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .bm-footer__contact li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 13.5px;
        color: #999;
        line-height: 1.6;
    }

    .bm-footer__contact-icon {
        width: 32px;
        height: 32px;
        background: #fff;
        border: 1px solid #e0d8d0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dfa974;
        font-size: 12px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* ── Divider ── */
    .bm-footer__divider {
        height: 1px;
        background: #e8e0d8;
        margin-bottom: 28px;
        text-align: center;
        line-height: 0;
    }

    .bm-footer__divider span {
        display: inline-block;
        width: 60px;
        height: 1px;
        background: #dfa974;
        vertical-align: top;
    }

    /* ── Copyright bar ── */
    .bm-footer__bottom {
        display: flex;
        justify-content: center;
        align-items: center;
        padding-bottom: 36px;
        font-size: 12px;
        color: #bbb;
        font-family: Arial, sans-serif;
    }

    /* ── Responsive ── */
    @media (max-width: 991px) {
        .bm-footer__grid {
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .bm-footer__brand {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 576px) {
        .bm-footer__grid {
            grid-template-columns: 1fr;
            gap: 36px;
            padding: 48px 0 36px;
        }

        .bm-footer__bottom {
            text-align: center;
        }
    }
</style>
