// Smooth page transitions - Right panel content only (stable layout)
document.addEventListener('DOMContentLoaded', function() {
    // Target specifically the form content inside the right panel
    const formContainer = document.querySelector('.bg-blue-50 > div') || document.querySelector('.bg-gray-50 > div');

    if (formContainer) {
        // Add smooth fade-in effect to the form content only (no layout shift)
        formContainer.style.opacity = '0';
        formContainer.style.transform = 'scale(0.95)';
        formContainer.style.transition = 'opacity 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94), transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';

        setTimeout(() => {
            formContainer.style.opacity = '1';
            formContainer.style.transform = 'scale(1)';
        }, 150);
    }

    // Handle navigation links with smooth transitions for form content only
    const navLinks = document.querySelectorAll('a[href*="/login"], a[href*="/register"]');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');

            if (formContainer) {
                // Smooth fade out effect for form content only (no layout shift)
                formContainer.style.opacity = '0';
                formContainer.style.transform = 'scale(0.95)';
                formContainer.style.transition = 'opacity 0.4s cubic-bezier(0.55, 0.085, 0.68, 0.53), transform 0.4s cubic-bezier(0.55, 0.085, 0.68, 0.53)';

                setTimeout(() => {
                    window.location.href = href;
                }, 400);
            } else {
                // Fallback if form container not found
                window.location.href = href;
            }
        });
    });

    // Add smooth hover effects to form elements
    const formInputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.2s ease-in-out';
        });

        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('button, .btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    button, .btn {
        position: relative;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
