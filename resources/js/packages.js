/*********************************
Ckeditor5
**********************************/
import CKEditor from '@ckeditor/ckeditor5-vue';
Vue.use( CKEditor );

/*********************************
Sweet alert
**********************************/
import VueSweetalert2 from 'vue-sweetalert2';

// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(VueSweetalert2);

/*********************************
Vuejs Paginate
**********************************/
import Paginate from 'vuejs-paginate'
Vue.component('paginate', Paginate)
