import { Controller } from '@hotwired/stimulus';
import {
    Carousel,
    Collapse,
    Sidenav,
    Input,
    initTE,
} from "tw-elements";



export default class extends Controller {
    connect() {
        initTE({ Carousel, Collapse, Sidenav, Input });
    }
}