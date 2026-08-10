<script setup>
defineProps({
    size: { type: String, default: 'lg' },
});
</script>

<template>
    <svg viewBox="0 0 520 520" xmlns="http://www.w3.org/2000/svg" class="h-auto w-full" role="img" aria-label="Illustration of a doctor">
        <defs>
            <linearGradient id="dp-bg" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#7DD2CD" stop-opacity="0.35" />
                <stop offset="100%" stop-color="#2C9B98" stop-opacity="0.18" />
            </linearGradient>
            <linearGradient id="dp-coat" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#FDFDFF" />
                <stop offset="100%" stop-color="#E8ECF4" />
            </linearGradient>
            <linearGradient id="dp-scrubs" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#47B7B3" />
                <stop offset="100%" stop-color="#207D7C" />
            </linearGradient>
            <radialGradient id="dp-hair" cx="50%" cy="35%" r="65%">
                <stop offset="0%" stop-color="#3B4A5E" />
                <stop offset="100%" stop-color="#22304E" />
            </radialGradient>
            <filter id="dp-shadow" x="-20%" y="-20%" width="140%" height="140%">
                <feDropShadow dx="0" dy="14" stdDeviation="18" flood-color="#1E6565" flood-opacity="0.18" />
            </filter>
        </defs>

        <!-- Background -->
        <rect width="520" height="520" rx="260" fill="url(#dp-bg)" />
        <circle cx="260" cy="260" r="196" fill="none" stroke="#FFFFFF" stroke-opacity="0.55" stroke-width="1.5" stroke-dasharray="3 9" />
        <circle cx="260" cy="260" r="150" fill="#FFFFFF" fill-opacity="0.35" />

        <!-- Decorative plus signs -->
        <g fill="#2C9B98" opacity="0.35">
            <rect x="78" y="120" width="14" height="4" rx="2" transform="translate(-2,2)" />
            <rect x="76" y="122" width="4" height="14" rx="2" />
            <rect x="428" y="300" width="14" height="4" rx="2" transform="translate(-2,2)" />
            <rect x="426" y="302" width="4" height="14" rx="2" />
            <rect x="120" y="398" width="10" height="3" rx="1.5" transform="translate(-1.5,1.5)" />
            <rect x="118.5" y="399.5" width="3" height="10" rx="1.5" />
        </g>

        <!-- ECG line -->
        <path d="M70 336 L150 336 L172 318 L196 356 L218 322 L238 336 L340 336 L360 322 L380 336 L450 336"
            fill="none" stroke="#2C9B98" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.5" />

        <!-- Figure (filtered group) -->
        <g filter="url(#dp-shadow)">
            <!-- Shoulders / coat -->
            <path d="M110 470 C110 360 170 318 260 318 C350 318 410 360 410 470 Z" fill="url(#dp-coat)" />
            <path d="M110 470 L112 462 C130 406 186 372 258 368 L262 368 C334 372 390 406 408 462 L410 470 Z" fill="#F4F6FB" />
            <path d="M206 368 C226 408 294 408 314 368 L308 470 L212 470 Z" fill="url(#dp-scrubs)" />
            <!-- Coat lapels -->
            <path d="M258 370 L228 368 L248 470 L258 470 Z" fill="#EDF1F8" />
            <path d="M262 370 L292 368 L272 470 L262 470 Z" fill="#EDF1F8" />
            <!-- Stethoscope -->
            <path d="M236 380 C226 352 244 336 258 336 C272 336 290 352 282 382 C276 404 258 412 244 408"
                fill="none" stroke="#22304E" stroke-width="5" stroke-linecap="round" />
            <circle cx="246" cy="412" r="9" fill="none" stroke="#47B7B3" stroke-width="4" />
            <circle cx="246" cy="412" r="3.5" fill="#47B7B3" />
            <!-- Collar -->
            <path d="M250 372 L258 352 L266 372" fill="#FDFDFF" />
        </g>

        <!-- Neck & head -->
        <g>
            <path d="M244 340 C244 316 256 306 260 306 C264 306 276 316 276 340 Z" fill="#E9B98F" />
            <ellipse cx="260" cy="252" rx="78" ry="86" fill="#EEC59A" />
            <ellipse cx="232" cy="286" rx="16" ry="20" fill="#E9B98F" />
            <ellipse cx="288" cy="286" rx="16" ry="20" fill="#E9B98F" />
            <path d="M260 330 C240 330 234 322 234 312 C234 292 242 282 260 282 C278 282 286 292 286 312 C286 322 280 330 260 330 Z" fill="#EEC59A" />
            <!-- Hair -->
            <path d="M182 236 C182 160 220 128 260 128 C300 128 338 160 338 236 C338 244 334 250 330 250 C322 250 320 242 316 240 C300 216 220 216 204 240 C200 242 198 250 190 250 C186 250 182 244 182 236 Z" fill="url(#dp-hair)" />
            <path d="M200 200 C216 178 244 170 262 176 C246 170 216 180 202 206 Z" fill="#1A2540" />
            <path d="M322 216 C316 200 302 190 288 186 C304 196 314 208 318 224 Z" fill="#1A2540" />
            <!-- Eyebrows -->
            <path d="M226 228 Q238 222 248 226" stroke="#4A3B2E" stroke-width="4" fill="none" stroke-linecap="round" />
            <path d="M272 226 Q282 222 294 228" stroke="#4A3B2E" stroke-width="4" fill="none" stroke-linecap="round" />
            <!-- Eyes -->
            <ellipse cx="238" cy="244" rx="8.5" ry="6.5" fill="#FFFFFF" />
            <ellipse cx="282" cy="244" rx="8.5" ry="6.5" fill="#FFFFFF" />
            <circle cx="239" cy="245" r="3.8" fill="#22304E" />
            <circle cx="281" cy="245" r="3.8" fill="#22304E" />
            <circle cx="240.5" cy="243.5" r="1.3" fill="#FFFFFF" />
            <circle cx="282.5" cy="243.5" r="1.3" fill="#FFFFFF" />
            <!-- Smile -->
            <path d="M244 276 Q260 290 276 276" stroke="#8A5A3B" stroke-width="4" fill="none" stroke-linecap="round" />
            <!-- Glasses -->
            <circle cx="238" cy="244" r="20" fill="none" stroke="#22304E" stroke-width="3.5" />
            <circle cx="282" cy="244" r="20" fill="none" stroke="#22304E" stroke-width="3.5" />
            <path d="M258 244 Q260 242 262 244" stroke="#22304E" stroke-width="3.5" fill="none" />
            <path d="M218 240 L212 236" stroke="#22304E" stroke-width="3.5" stroke-linecap="round" />
            <path d="M302 240 L308 236" stroke="#22304E" stroke-width="3.5" stroke-linecap="round" />
            <!-- Cheeks -->
            <ellipse cx="222" cy="268" rx="10" ry="6" fill="#F5A37B" opacity="0.25" />
            <ellipse cx="298" cy="268" rx="10" ry="6" fill="#F5A37B" opacity="0.25" />
        </g>
    </svg>
</template>
