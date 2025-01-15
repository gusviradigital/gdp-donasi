export default function initSearch() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchModal = document.getElementById('search-modal');
    const searchClose = document.querySelector('.search-close');
    const searchInput = searchModal?.querySelector('input[type="search"]');

    if (!searchToggle || !searchModal || !searchClose || !searchInput) return;

    // Toggle search modal
    function toggleSearch() {
        const isHidden = searchModal.classList.contains('hidden');
        
        // Show/hide modal
        searchModal.classList.toggle('hidden');
        
        // Update ARIA attributes
        searchToggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
        
        // Focus input when opening
        if (isHidden) {
            searchInput.focus();
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        } else {
            // Restore body scroll
            document.body.style.overflow = '';
        }
    }

    // Close on escape key
    function handleEscape(event) {
        if (event.key === 'Escape' && !searchModal.classList.contains('hidden')) {
            toggleSearch();
        }
    }

    // Close on click outside
    function handleClickOutside(event) {
        const modalPanel = searchModal.querySelector('.relative.bg-white');
        if (!searchModal.classList.contains('hidden') && 
            !modalPanel.contains(event.target) && 
            !searchToggle.contains(event.target)) {
            toggleSearch();
        }
    }

    // Event listeners
    searchToggle.addEventListener('click', toggleSearch);
    searchClose.addEventListener('click', toggleSearch);
    document.addEventListener('keydown', handleEscape);
    document.addEventListener('click', handleClickOutside);

    // Clean up function
    return () => {
        searchToggle.removeEventListener('click', toggleSearch);
        searchClose.removeEventListener('click', toggleSearch);
        document.removeEventListener('keydown', handleEscape);
        document.removeEventListener('click', handleClickOutside);
    };
} 