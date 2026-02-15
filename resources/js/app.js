import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-datepicker]").forEach((el) => {
    flatpickr(el, {
      dateFormat: "Y-m-d",
      allowInput: true,
    });
  });
});