<div class="page-loader-wrapper">
    <div class="page-loader">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 431.96 123.62" style="overflow: visible">
            <defs>
                <filter id="blurMe">
                    <feGaussianBlur in="SourceGraphic" stdDeviation="1" />
                </filter>
                <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:rgb(50,50,50);stop-opacity:1">
                        <animate attributeName="stop-color" values="rgb(50,50,50);rgb(120,120,120);rgb(160,160,160);rgb(120,120,120);rgb(50,50,50)" dur="1s" repeatCount="indefinite" />
                    </stop>
                    <stop offset="100%" style="stop-color:rgb(120,120,120);stop-opacity:1">
                        <animate attributeName="stop-color" values="rgb(120,120,120);rgb(160,160,160);rgb(120,120,120);rgb(50,50,50);rgb(120,120,120)" dur="1s" repeatCount="indefinite" />
                    </stop>
                </linearGradient>
                <radialGradient id="wheelGrad" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
                    <stop offset="78%" style="stop-color:rgb(192,192,192);" />
                    <stop offset="78%" style="stop-color:rgb(70,70,70);" />
                </radialGradient>
                <style>
                    @keyframes shake {
                        0% {
                            transform: translateY(0);
                        }

                        25% {
                            transform: translateY(-3px);
                        }

                        50% {
                            transform: translateY(0);
                        }

                        75% {
                            transform: translateY(1px);
                        }

                        100% {
                            transform: translateY(0);
                        }
                    }

                    @keyframes rotate {
                        0% {
                            transform: rotate(0deg);
                        }

                        100% {
                            transform: rotate(360deg);
                        }
                    }

                    @keyframes line {
                        0% {
                            stroke-dashoffset: 100;
                        }

                        50% {
                            stroke-dashoffset: 0;
                        }

                        100% {
                            stroke-dashoffset: -100;
                        }
                    }

                    .vehicle-left-wheel {
                        fill: url(#wheelGrad);
                        transform-origin: 90px 91px;
                        animation: rotate 500ms linear infinite;
                    }

                    .vehicle-right-wheel {
                        fill: url(#wheelGrad);
                        transform-origin: 333px 91px;
                        animation: rotate 500ms linear infinite;
                    }

                    .wheel-blur-1 {
                        opacity: 0.3;
                        animation-delay: 50ms;
                    }

                    .wheel-blur-2 {
                        opacity: 0.3;
                        animation-delay: 100ms;
                    }

                    .vehicle-body {
                        fill: url(#grad1);
                        animation: shake 1s ease-in-out infinite alternate;
                    }

                    .car-line {
                        fill: white;
                        transform-origin: center right;
                        stroke-dasharray: 100;
                        animation: line 0.8s linear infinite reverse, shake 1s infinite alternate;
                    }

                    .car-line-1 {
                        animation-delay: 300ms;
                    }

                    .car-line-2 {
                        animation-delay: 150ms;
                    }
                </style>
            </defs>
            <path class="vehicle-body" d="M426.69,108.13c-1.01-.96-1.55-2.85-1.57-4.33-.04-3.49.16-7.01.64-10.46,1.17-8.49-1.77-15.47-8.39-20.4-4.59-3.42-10.14-5.55-15.17-8.41-2.96-1.68-6.26-3.19-8.55-5.59-7.15-7.52-15.36-12.73-25.86-13.72-20.55-1.94-41.07-4.36-61.66-5.56-13.46-.78-25.8-3.32-37.17-11.07-9.19-6.26-19.31-11.16-28.83-16.96C224.45,2.07,207.37-.61,189.33.11c-30.7,1.22-60.34,7.82-89.43,17.17-18.84,6.05-37.51,12.64-56.28,18.89-2.39.8-4.96,1.06-7.45,1.57l-1.21-1.47c1.29-2.17,2.7-4.28,3.81-6.54.69-1.41,1.59-3.24,1.19-4.5-.34-1.08-2.3-1.84-3.69-2.37-2-.76-4.41-.73-6.15-1.83-6.98-4.43-14.75-3.43-22.34-3.63-1.08-.03-3.11,1.05-3.11,1.62,0,1.26.68,2.94,1.66,3.69,2.37,1.8,5.02,3.25,7.64,4.68,3.99,2.18,4.2,4.76,1.02,7.81-3.06,2.94-3.79,5.74.16,8.61,3.08,2.24,2.51,4.63.05,7.08-3.43,3.4-6.63,7.05-10.16,10.33-3.85,3.58-5.36,7.78-4.96,12.97.44,5.76,1.15,11.62.6,17.32-.91,9.5.01,11.15,9.4,12.12,8.78.91,17.55,1.9,26.32,2.79,4.87.5,9.75.87,15.13,1.35,0-6.09-.22-11.41.04-16.71.7-13.88,6.2-25.07,18.72-32.1,26.14-14.69,58.48,3.74,59.19,33.74.12,5.11.02,10.22.02,16.08h164.97c0-3.26-.39-6.61.08-9.85,1.02-7.04,1.87-14.2,3.89-20.99,4.31-14.49,14.7-22.18,29.55-23.72,14.95-1.55,27.8,2.55,36.64,15.45,5.34,7.79,7.29,16.66,7.01,26.08-.17,5.77-.03,11.54-.03,17.58h58.34c.68-.78,1.35-1.57,2.03-2.35-1.79-.93-3.86-1.55-5.27-2.88ZM11.82,60.79c-.1-.55-.2-1.1-.3-1.65,2.48-.31,5.11-.23,7.4-1.05,2.44-.88,4.57-2.62,6.83-3.98-.16-.45-.31-.9-.47-1.35-1.47-.46-2.94-.91-4.41-1.37.04-.49.07-.99.11-1.48,7.45-.62,14.72,1.12,23.06,2.71-10.27,7.93-20.75,10.15-32.23,8.17ZM148.11,36.55c-16.26-.94-32.54-1.74-48.78-3.03-2.76-.22-5.35-2.53-8.02-3.88,2.45-1.39,4.74-3.26,7.37-4.08,13.63-4.28,27.37-8.21,41.01-12.46,3.95-1.23,5.8-.28,6.56,3.86,1.15,6.34,2.69,12.6,4.11,19.05-1,.26-1.63.59-2.24.55ZM154.31,53.87c7.95-4.18,14.05-3.99,23.2.74-5.34,4.54-16.73,4.18-23.2-.74ZM232.04,29.38c-1.35,2.26-.73,5.69-1,8.84-5.01,0-11.12.09-17.21-.02-15.14-.25-30.27-.69-45.41-.83-3.37-.03-5.05-1.11-6.05-4.4-2.27-7.4-4.94-14.68-7.66-22.63,30.69-5.54,59.99-9.09,84.34,13.51-2.22,1.65-5.52,3.04-7,5.51ZM385.5,64.42c-6.89-4.23-13.66-8.68-20.43-13.05,7.95-2.42,20.04,3.69,24.16,12.69-1.39.16-2.92.85-3.73.36Z" />
            <path class="vehicle-right-wheel" filter="url(#blurMe)" d="M333.35,59.78c-17.43.01-31.81,14.31-31.9,31.7-.09,17.73,14.21,32.15,31.88,32.15,17.47,0,31.81-14.18,31.98-31.61.17-17.5-14.45-32.25-31.96-32.23ZM331.39,66.94c-.49,6.27-.85,10.88-1.33,16.96-4.88-3.51-8.69-6.25-13.79-9.92,5.3-2.47,9.56-4.46,15.12-7.04ZM309.71,86.94c5.35,2.64,9.31,4.59,14.16,6.98-4.15,2.69-7.58,4.91-12.19,7.89-.7-5.3-1.25-9.48-1.97-14.87ZM320.22,111.58c4.1-3.82,7.2-6.72,11.11-10.36.98,4.78,1.78,8.66,2.87,13.94-4.88-1.25-8.83-2.26-13.98-3.58ZM333.59,97.88c-1.83-2.82-3.73-4.46-3.66-6.01.06-1.3,2.43-2.49,3.78-3.73,1.3,1.42,3.36,2.71,3.64,4.31.22,1.2-1.85,2.81-3.76,5.43ZM345.36,70.44c3.63,4.04,6.64,7.39,10.46,11.65-5.97,1.15-10.38,2.01-15.76,3.05,1.83-5.08,3.31-9.18,5.3-14.7ZM348.81,109.6c-.63-.1-1.27-.19-1.9-.29-1.82-3.78-3.65-7.55-5.47-11.33,4.12-.49,8.23-1,12.35-1.46.46-.05.96.18,2.8.57-2.77,4.46-5.28,8.48-7.78,12.51Z" />
            <path class="vehicle-right-wheel wheel-blur-1" filter="url(#blurMe)" d="M333.35,59.78c-17.43.01-31.81,14.31-31.9,31.7-.09,17.73,14.21,32.15,31.88,32.15,17.47,0,31.81-14.18,31.98-31.61.17-17.5-14.45-32.25-31.96-32.23ZM331.39,66.94c-.49,6.27-.85,10.88-1.33,16.96-4.88-3.51-8.69-6.25-13.79-9.92,5.3-2.47,9.56-4.46,15.12-7.04ZM309.71,86.94c5.35,2.64,9.31,4.59,14.16,6.98-4.15,2.69-7.58,4.91-12.19,7.89-.7-5.3-1.25-9.48-1.97-14.87ZM320.22,111.58c4.1-3.82,7.2-6.72,11.11-10.36.98,4.78,1.78,8.66,2.87,13.94-4.88-1.25-8.83-2.26-13.98-3.58ZM333.59,97.88c-1.83-2.82-3.73-4.46-3.66-6.01.06-1.3,2.43-2.49,3.78-3.73,1.3,1.42,3.36,2.71,3.64,4.31.22,1.2-1.85,2.81-3.76,5.43ZM345.36,70.44c3.63,4.04,6.64,7.39,10.46,11.65-5.97,1.15-10.38,2.01-15.76,3.05,1.83-5.08,3.31-9.18,5.3-14.7ZM348.81,109.6c-.63-.1-1.27-.19-1.9-.29-1.82-3.78-3.65-7.55-5.47-11.33,4.12-.49,8.23-1,12.35-1.46.46-.05.96.18,2.8.57-2.77,4.46-5.28,8.48-7.78,12.51Z" />
            <path class="vehicle-right-wheel wheel-blur-2" filter="url(#blurMe)" d="M333.35,59.78c-17.43.01-31.81,14.31-31.9,31.7-.09,17.73,14.21,32.15,31.88,32.15,17.47,0,31.81-14.18,31.98-31.61.17-17.5-14.45-32.25-31.96-32.23ZM331.39,66.94c-.49,6.27-.85,10.88-1.33,16.96-4.88-3.51-8.69-6.25-13.79-9.92,5.3-2.47,9.56-4.46,15.12-7.04ZM309.71,86.94c5.35,2.64,9.31,4.59,14.16,6.98-4.15,2.69-7.58,4.91-12.19,7.89-.7-5.3-1.25-9.48-1.97-14.87ZM320.22,111.58c4.1-3.82,7.2-6.72,11.11-10.36.98,4.78,1.78,8.66,2.87,13.94-4.88-1.25-8.83-2.26-13.98-3.58ZM333.59,97.88c-1.83-2.82-3.73-4.46-3.66-6.01.06-1.3,2.43-2.49,3.78-3.73,1.3,1.42,3.36,2.71,3.64,4.31.22,1.2-1.85,2.81-3.76,5.43ZM345.36,70.44c3.63,4.04,6.64,7.39,10.46,11.65-5.97,1.15-10.38,2.01-15.76,3.05,1.83-5.08,3.31-9.18,5.3-14.7ZM348.81,109.6c-.63-.1-1.27-.19-1.9-.29-1.82-3.78-3.65-7.55-5.47-11.33,4.12-.49,8.23-1,12.35-1.46.46-.05.96.18,2.8.57-2.77,4.46-5.28,8.48-7.78,12.51Z" />
            <path class="vehicle-left-wheel" filter="url(#blurMe)" d="M89.95,59.76c-17.72.05-31.86,14.42-31.75,32.26.11,17.79,14.43,31.79,32.31,31.6,17.46-.19,31.39-14.52,31.27-32.16-.12-17.54-14.39-31.74-31.83-31.7ZM88.04,67.04c-.51,6.38-.87,10.92-1.34,16.82-4.88-3.48-8.6-6.13-13.8-9.84,5.38-2.48,9.68-4.46,15.14-6.98ZM65.96,86.71c5.61,2.76,9.74,4.8,14.59,7.2-4.16,2.67-7.57,4.85-12.14,7.78-.83-5.05-1.51-9.21-2.46-14.97ZM76.8,111.59c4.19-3.9,7.28-6.77,11.17-10.39,1.03,4.92,1.84,8.77,2.93,13.94-4.81-1.21-8.76-2.21-14.09-3.56ZM90.79,95.78c-1.58-.2-3.66-2.04-4.11-3.58-.28-.95,1.99-3.69,3.26-3.79,1.57-.12,3.29,1.66,6.22,3.36-2.59,2.05-4.13,4.17-5.37,4.01ZM102.03,70.26c3.73,4.26,6.66,7.6,10.35,11.82-5.77,1.12-10.15,1.97-15.59,3.03,1.79-5.06,3.23-9.15,5.24-14.85ZM104.85,110.57c-2.6-4.15-4.46-7.02-6.21-9.96-1.68-2.82-.4-4.02,2.5-4.05,3.64-.04,7.28-.01,12.5-.01-3.12,4.98-5.64,9-8.79,14.03Z" />
            <path class="vehicle-left-wheel wheel-blur-1" filter="url(#blurMe)" d="M89.95,59.76c-17.72.05-31.86,14.42-31.75,32.26.11,17.79,14.43,31.79,32.31,31.6,17.46-.19,31.39-14.52,31.27-32.16-.12-17.54-14.39-31.74-31.83-31.7ZM88.04,67.04c-.51,6.38-.87,10.92-1.34,16.82-4.88-3.48-8.6-6.13-13.8-9.84,5.38-2.48,9.68-4.46,15.14-6.98ZM65.96,86.71c5.61,2.76,9.74,4.8,14.59,7.2-4.16,2.67-7.57,4.85-12.14,7.78-.83-5.05-1.51-9.21-2.46-14.97ZM76.8,111.59c4.19-3.9,7.28-6.77,11.17-10.39,1.03,4.92,1.84,8.77,2.93,13.94-4.81-1.21-8.76-2.21-14.09-3.56ZM90.79,95.78c-1.58-.2-3.66-2.04-4.11-3.58-.28-.95,1.99-3.69,3.26-3.79,1.57-.12,3.29,1.66,6.22,3.36-2.59,2.05-4.13,4.17-5.37,4.01ZM102.03,70.26c3.73,4.26,6.66,7.6,10.35,11.82-5.77,1.12-10.15,1.97-15.59,3.03,1.79-5.06,3.23-9.15,5.24-14.85ZM104.85,110.57c-2.6-4.15-4.46-7.02-6.21-9.96-1.68-2.82-.4-4.02,2.5-4.05,3.64-.04,7.28-.01,12.5-.01-3.12,4.98-5.64,9-8.79,14.03Z" />
            <path class="vehicle-left-wheel wheel-blur-2" filter="url(#blurMe)" d="M89.95,59.76c-17.72.05-31.86,14.42-31.75,32.26.11,17.79,14.43,31.79,32.31,31.6,17.46-.19,31.39-14.52,31.27-32.16-.12-17.54-14.39-31.74-31.83-31.7ZM88.04,67.04c-.51,6.38-.87,10.92-1.34,16.82-4.88-3.48-8.6-6.13-13.8-9.84,5.38-2.48,9.68-4.46,15.14-6.98ZM65.96,86.71c5.61,2.76,9.74,4.8,14.59,7.2-4.16,2.67-7.57,4.85-12.14,7.78-.83-5.05-1.51-9.21-2.46-14.97ZM76.8,111.59c4.19-3.9,7.28-6.77,11.17-10.39,1.03,4.92,1.84,8.77,2.93,13.94-4.81-1.21-8.76-2.21-14.09-3.56ZM90.79,95.78c-1.58-.2-3.66-2.04-4.11-3.58-.28-.95,1.99-3.69,3.26-3.79,1.57-.12,3.29,1.66,6.22,3.36-2.59,2.05-4.13,4.17-5.37,4.01ZM102.03,70.26c3.73,4.26,6.66,7.6,10.35,11.82-5.77,1.12-10.15,1.97-15.59,3.03,1.79-5.06,3.23-9.15,5.24-14.85ZM104.85,110.57c-2.6-4.15-4.46-7.02-6.21-9.96-1.68-2.82-.4-4.02,2.5-4.05,3.64-.04,7.28-.01,12.5-.01-3.12,4.98-5.64,9-8.79,14.03Z" />
            <line class="car-line" x1="-150" y1="0" x2="130" y2="0" stroke="white" stroke-width="1" />
            <line class="car-line car-line-1" x1="-200" y1="45" x2="0" y2="45" stroke="white" stroke-width="1" />
            <line class="car-line car-line-2" x1="-250" y1="90" x2="-20" y2="90" stroke="white" stroke-width="1" />
        </svg>
    </div>
</div>