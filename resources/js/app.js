import './bootstrap';

import Alpine from 'alpinejs';
import ujs from '@rails/ujs';
import Chart from 'chart.js/auto';
import * as Utils from'./utils.js';

window.Alpine = Alpine;
window.Chart = Chart;
window.Utils = Utils;

Alpine.start();
ujs.start();
