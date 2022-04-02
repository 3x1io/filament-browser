import axios from 'axios';
import Vue from 'vue';
import VueCodemirror from 'vue-codemirror'
import 'codemirror/lib/codemirror.css'
import 'codemirror/mode/php/php.js'
import 'codemirror/mode/javascript/javascript.js'
import 'codemirror/mode/css/css.js';
import 'codemirror/mode/htmlmixed/htmlmixed.js';
import 'codemirror/addon/edit/matchbrackets';
import 'codemirror/mode/xml/xml.js';
import 'codemirror/mode/clike/clike.js';


// import theme style
import 'codemirror/theme/base16-dark.css'

Vue.use(VueCodemirror)

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

Vue.component('browser', {
    data() {
        return {
            history: {},
            files: [],
            fileContent: false,
            cmOptions: {
                tabSize: 4,
                mode: "application/x-httpd-php",
                theme: 'base16-dark',
                lineNumbers: true,
                line: true,
                matchBrackets: true,
                indentUnit: 4,
                indentWithTabs: true,
                lineWrapping: true,
            },
            imagePath: false,
            path: ""
        }
    },
    mounted(){
        this.files = this.$props.collection;
        this.history = this.$props.collection;
    },
    methods:{
        getFolder(data){
            axios.post(this.$props.url+'/json', {
                folder_path: data.path,
                folder_name: data.name,
                type: "folder"
            }).then(response => {
                this.files = {
                    folders: response.data.folders,
                    files: response.data.files,
                }
                this.history = response.data;
            })
        },
        getFile(data){
            axios.post(this.$props.url+'/json', {
                file_path: data.path,
                file_name: data.name,
                type: "file"
            }).then(response => {
                this.files = {
                    folders: response.data.folders,
                    files: response.data.files,
                }
                this.path=  response.data.path;
                this.history = response.data;
                if(response.data.ex == 'php'){
                    this.cmOptions.mode = 'application/x-httpd-php'
                }
                else if(response.data.ex == 'js' || response.data.ex == 'json' || response.data.ex == 'lock'){
                    this.cmOptions.mode =  {
                        name: 'javascript',
                        json: true
                    }
                }
                else if(response.data.ex == 'css'){
                    this.cmOptions.mode ='text/css'
                }

                if(response.data.ex == 'webp' || response.data.ex == 'svg' || response.data.ex == 'png' || response.data.ex == 'jpg' || response.data.ex == 'jpeg' || response.data.ex == 'tif' || response.data.ex == 'gif' || response.data.ex == 'ico' ){
                    this.imagePath = 'data:image/png;base64,'+response.data.file;
                }
                else {
                    this.fileContent = response.data.file;
                }

            })
        },
        goHome(){
            axios.post(this.$props.url+'/json').then(data => {
                this.files = {
                    folders: data.data.folders,
                    files: data.data.files,
                    type: "home"
                }
                this.history = data.data;
                this.fileContent = false;
                this.imagePath = false;
            })
        },
        goBack(){
            axios.post(this.$props.url+'/json', {
                folder_path: this.history.back_path,
                folder_name: this.history.back_name,
                type: "back"
            }).then(data => {
                this.files = {
                    folders: data.data.folders,
                    files: data.data.files,
                }
                this.history = data.data;
                this.fileContent = false;
                this.imagePath = false;
            })
        },
        saveFile(){
            axios.post(this.$props.url+'/json', {
                path: this.path,
                content: this.fileContent,
                type: "save"
            }).then(data => {
                if(data.data.success){
                    this.$swal({
                        toast: true,
                        position: 'top-end',
                        width: 600,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        timer: 3000,
                        icon: 'success',
                        title: 'File Has Been Saved Success!'
                    });
                }
            })
        }
    },
    props:{
        url: String,
        collection: Object
    }
});
