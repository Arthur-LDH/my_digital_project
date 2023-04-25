import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['sidebar'];
    isOpen = false;

    connect() {
        const header = document.getElementById('header-menu');
        header.addEventListener('click', event => {
            this.isOpen = !this.isOpen;
            this.toggleSidebar();
        })
    }

    toggleSidebar() {
        if (this.isOpen) {
            this.sidebarTarget.classList.add('active');
        } else {
            this.sidebarTarget.classList.remove('active');
        }
    }
}