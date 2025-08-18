// Property Tabs Functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabTriggers = document.querySelectorAll('.property-tab-trigger');
    const tabContents = document.querySelectorAll('.property-tab-content');

    // Function to switch tabs
    function switchTab(tabId) {
        // Hide all tab contents
        tabContents.forEach(content => {
            content.classList.remove('active');
        });

        // Show the selected tab content
        const activeTab = document.querySelector(`.property-tab-content[data-tab="${tabId}"]`);
        if (activeTab) {
            activeTab.classList.add('active');
        }

        // Update tab triggers
        tabTriggers.forEach(trigger => {
            if (trigger.dataset.tab === tabId) {
                trigger.classList.add('text-teal-600', 'border-teal-600');
                trigger.classList.remove('text-gray-500', 'hover:text-gray-700');
            } else {
                trigger.classList.remove('text-teal-600', 'border-teal-600');
                trigger.classList.add('text-gray-500', 'hover:text-gray-700');
            }
        });
    }

    // Add click event listeners to tab triggers
    tabTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            switchTab(tabId);
        });
    });

    // Initialize with the first tab active if none is active
    const activeTab = document.querySelector('.property-tab-trigger.text-teal-600');
    if (!activeTab && tabTriggers.length > 0) {
        switchTab(tabTriggers[0].dataset.tab);
    }
});
