<x-guest-layout>
<style>


    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --bg:        #FFFFFF;
        --surface:   #FFFFFF;
        --dark:      #1C1E19;
        --mid:       #5A5E52;
        --light:     #9A9E92;
        --border:    #E4E2DC;
        --accent:    #2F3526;
        --success-bg:#EDF4EE;
        --success-fg:#3A7D44;
    }

    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        background: var(--bg);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }



    .ve-wrap {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 460px;
    }

    .ve-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2.5rem 2.5rem 2rem;
        box-shadow:
            0 1px 2px rgba(0,0,0,.04),
            0 8px 32px rgba(0,0,0,.06);
        animation: fadeUp .5s ease both;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* icon */
    .ve-icon-wrap {
        width: 56px;
        height: 56px;
        margin: 0 auto 1.75rem;
        background: #F0EEE8;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
    }

    /* heading */
    .ve-eyebrow {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 9px;
        font-weight: 600;
        letter-spacing: .3em;
        text-transform: uppercase;
        color: var(--light);
        text-align: center;
        display: block;
        margin-bottom: .6rem;
    }

    .ve-heading {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 1.6rem;
        font-weight: 600;
        color: var(--dark);
        text-align: center;
        line-height: 1.2;
        margin-bottom: 1.25rem;
        letter-spacing: -.01em;
    }

    .ve-heading em {
        font-style: normal;
        font-weight: 300;
    }

    /* divider */
    .ve-rule {
        border: none;
        border-top: 1px solid var(--border);
        margin: 1.5rem 0;
    }

    /* description */
    .ve-desc {
        font-size: 13.5px;
        color: var(--mid);
        line-height: 1.8;
        text-align: center;
        margin-bottom: 1.25rem;
    }

    .ve-desc strong {
        color: var(--dark);
        font-weight: 500;
    }

    /* success status */
    .ve-status {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: var(--success-fg);
        background: var(--success-bg);
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 1.25rem;
        font-weight: 500;
    }

    /* buttons */
    .ve-btn-primary {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px 24px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: .25em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background .2s, transform .15s;
        text-decoration: none;
        margin-bottom: 1.5rem;
    }

    .ve-btn-primary:hover {
        background: #3b4430;
        transform: translateY(-1px);
    }

    .ve-resend-label {
        font-size: 12px;
        color: var(--light);
        text-align: center;
        margin-bottom: .75rem;
    }

    .ve-btn-ghost {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 13px 24px;
        background: transparent;
        color: var(--accent);
        border: 1px solid var(--border);
        border-radius: 10px;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: .22em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background .2s, color .2s, border-color .2s;
    }

    .ve-btn-ghost:hover {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }

    /* logout */
    .ve-footer {
        text-align: center;
        margin-top: 1.5rem;
    }

    .ve-logout {
        font-size: 11px;
        color: var(--light);
        text-decoration: none;
        letter-spacing: .06em;
        transition: color .2s;
        background: none;
        border: none;
        cursor: pointer;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }

    .ve-logout:hover { color: var(--dark); }
</style>

<div class="ve-wrap">

    <div class="ve-card">

        {{-- Icon --}}
        <div class="ve-icon-wrap">
            <svg width="22" height="22" fill="none" stroke="#2F3526" viewBox="0 0 24 24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        {{-- Heading --}}
        <span class="ve-eyebrow">One More Step</span>
        <h1 class="ve-heading">Verify Your <em>Email</em></h1>

        {{-- Status --}}
        @if (session('status') == 'verification-link-sent')
            <div class="ve-status">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                A new verification link has been sent to your email.
            </div>
        @endif

        {{-- Description --}}
        <p class="ve-desc">
            We have sent a verification link to <strong>your email</strong>.<br>
            Open the email and click the verification button to activate your account.
        </p>

        <hr class="ve-rule">

        {{-- Resend --}}
        <p class="ve-resend-label">Didn't receive the email? Check your <strong>Spam</strong> folder or resend.</p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="ve-btn-ghost">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/>
                </svg>
                Resend Verification
            </button>
        </form>

        {{-- Logout --}}
        <div class="ve-footer">
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="ve-logout">Log Out</button>
            </form>
        </div>

    </div>
</div>

</x-guest-layout>