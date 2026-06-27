<style>
    .error-page-wrap {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        padding: 3rem 0;
    }

    .error-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(21, 61, 111, 0.1);
        padding: 3rem 2.5rem 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .error-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    }

    .error-illustration {
        width: 220px;
        max-width: 70%;
        margin: 0 auto 1.5rem;
    }

    .error-stamp {
        transform-origin: center;
        animation: stampSettle 0.55s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }

    @keyframes stampSettle {
        0% {
            opacity: 0;
            transform: translate(160px, 150px) rotate(-12deg) scale(2.2);
        }
        70% {
            opacity: 1;
        }
        100% {
            opacity: 1;
            transform: translate(160px, 150px) rotate(-12deg) scale(1);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .error-stamp {
            animation: none;
        }
    }

    .error-eyebrow {
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--accent-dark);
        margin-bottom: 0.5rem;
    }

    .error-heading {
        color: var(--primary);
        font-weight: 700;
        font-size: 1.85rem;
        margin-bottom: 0.75rem;
    }

    .error-body {
        color: var(--gray-600);
        font-size: 1.02rem;
        max-width: 30rem;
        margin: 0 auto 1.75rem;
        line-height: 1.6;
    }

    .error-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .error-code-tag {
        font-size: 0.8rem;
        color: var(--gray-500);
        font-weight: 600;
        letter-spacing: 0.03em;
        margin-bottom: 0;
    }

    @media (max-width: 576px) {
        .error-card {
            padding: 2.25rem 1.5rem 2rem;
            border-radius: 18px;
        }

        .error-heading {
            font-size: 1.5rem;
        }

        .error-actions {
            flex-direction: column;
        }

        .error-actions .btn {
            width: 100%;
        }
    }
</style>
