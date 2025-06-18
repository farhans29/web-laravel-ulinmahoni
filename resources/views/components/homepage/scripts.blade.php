// Property tabs functionality
const propertyTriggers = document.querySelectorAll('.property-tab-trigger');
const propertyContents = document.querySelectorAll('.property-tab-content');

propertyTriggers.forEach(trigger => {
    trigger.addEventListener('click', () => {
        const tabName = trigger.getAttribute('data-tab');
        
        // Update trigger styles
        propertyTriggers.forEach(t => {
            t.classList.remove('text-teal-600', 'border-b-2', 'border-teal-600');
            t.classList.add('text-gray-500');
        });
        trigger.classList.remove('text-gray-500');
        trigger.classList.add('text-teal-600', 'border-b-2', 'border-teal-600');
        
        // Update content visibility
        propertyContents.forEach(content => {
            if (content.getAttribute('data-tab') === tabName) {
                content.classList.remove('hidden');
                content.classList.add('active');
            } else {
                content.classList.add('hidden');
                content.classList.remove('active');
            }
        });
    });
});

// Area tabs functionality
const areaTriggers = document.querySelectorAll('.area-tab-trigger');
const areaContents = document.querySelectorAll('.area-tab-content');

areaTriggers.forEach(trigger => {
    trigger.addEventListener('click', () => {
        const tabName = trigger.getAttribute('data-tab');
        
        // Update trigger styles
        areaTriggers.forEach(t => {
            t.classList.remove('text-teal-600', 'border-b-2', 'border-teal-600');
            t.classList.add('text-gray-500');
        });
        trigger.classList.remove('text-gray-500');
        trigger.classList.add('text-teal-600', 'border-b-2', 'border-teal-600');
        
        // Update content visibility
        areaContents.forEach(content => {
            if (content.getAttribute('data-tab') === tabName) {
                content.classList.remove('hidden');
                content.classList.add('active');
            } else {
                content.classList.add('hidden');
                content.classList.remove('active');
            }
        });
    });
});

// Video functionality
const video = document.getElementById('heroVideo');
const playPauseBtn = document.getElementById('playPauseBtn');
const playPauseIcon = document.getElementById('playPauseIcon');

if (video && playPauseBtn && playPauseIcon) {
    let isPlaying = true;

    video.play().catch(error => {
        console.error("Video autoplay failed:", error);
        isPlaying = false;
        playPauseIcon.classList.remove('fa-pause');
        playPauseIcon.classList.add('fa-play');
    });

    playPauseBtn.addEventListener('click', function() {
        if (isPlaying) {
            video.pause();
            playPauseIcon.classList.remove('fa-pause');
            playPauseIcon.classList.add('fa-play');
        } else {
            video.play();
            playPauseIcon.classList.remove('fa-play');
            playPauseIcon.classList.add('fa-pause');
        }
        isPlaying = !isPlaying;
    });
} 