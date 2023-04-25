import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const header = document.getElementById('header-btn');
        const sidebar = document.getElementById('sidebar');
        header.addEventListener('click', event => {
            sidebar.classList.toggle('active');
        })
    }   
}