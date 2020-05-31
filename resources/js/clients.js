/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');
let api_token = document.head.querySelector('meta[name="cl-code"]');

let axios = (function() {
    let _axios = null;
    try {
        _axios = require('axios');
    } catch (e) {
        console.error('Axios package not available');
    }

    if(_axios) {
        _axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        if (token) {
            _axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            if (api_token) {
                _axios.defaults.headers.common['Authorization'] = 'Bearer ' + api_token.content;
            }
            _axios.defaults.headers.common['Accept'] = 'application/json';
            // _axios.defaults.headers.common['Content-Type'] = 'application/json';

        } else {
            console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
        }
    }

    return _axios;
})();


let xhttp = (function() {

    let _response  = {
        data: {},
        headers: {},
        request: {},
        status: '',
        statusText: '',
    }

    let responseHeadersToJson = (allResponseHeaders) => {
        let array = allResponseHeaders.split('\r\n');
        return array.reduce(function (acc, current, i){
              var parts = current.split(': ');
              if(parts[0]) {
                  acc[parts[0]] = parts[1];
              }
              return acc;
        }, {});
    }

    let generateResponse = (xhr) => {
        let response = Object.assign({}, _response);
        response.request = xhr;
        response.data = xhr.response;
        response.headers = responseHeadersToJson(xhr.getAllResponseHeaders());
        response.status = xhr.status;
        response.statusText = xhr.statusText;

        return response;
    }

    class Client {
        constructor () {
            this.loadCallback = function () {};
            this.errorCallback = function () {};
            this.endCallback = function () {};

            this.xhr = new XMLHttpRequest();

            this.xhr.onabort = function handleAbort () {
                console.log('onabort: ', this);
            };
            this.xhr.onerror = function handleError () {
                console.log('onerror: ', this);
            };
            this.xhr.onreadystatechange = function handleLoad () {
                // console.log('onreadystatechange: ', this);
            };
            this.xhr.ontimeout = function handleTimeout () {
                console.log('ontimeout: ', this);
            };
        }

        run(method, url, formData) {
            this.addListeners();
            this.xhr.open(method, url, true);
            this.setRequestHeader();
            this.send(formData)
            return this;
        }

        addListeners() {
            let self = this;
            // this.xhr.addEventListener('loadstart', function () {
            //     self.handleEvent(event);
            // });
            this.xhr.addEventListener('load', function () {
                self.load(event, self.xhr);
            });
            this.xhr.addEventListener('loadend', function () {
                self.end(event, self.xhr);
            });
            this.xhr.addEventListener('error', function () {
                self.error(event, self.xhr);
            });
            // this.xhr.addEventListener('progress', handleEvent);
            // this.xhr.addEventListener('abort', handleEvent);
        }

        setRequestHeader () {
            if (token) {
                this.xhr.setRequestHeader('X-CSRF-TOKEN', token.content);
                this.xhr.setRequestHeader('X-xhruested-With', 'XMLHttpxhruest');
                if (api_token) {
                    this.xhr.setRequestHeader('Authorization', 'Bearer ' + api_token.content);
                }
                this.xhr.setRequestHeader('Accept', 'application/json');
                this.xhr.responseType = 'json';
            }else {
                console.error('CSRF token/api-token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
            }
        }

        send (formData) {
            if(formData) {
                this.xhr.send(formData);
                return;
            }
            this.xhr.send();
        }

        handleEvent (event) {
            console.log(`${e.type}: ${e.loaded} bytes transferred\n`);
        }

        load (event, xhr) {
            this.loadCallback(generateResponse(xhr));

            if(xhr.status != 200)  {
                this.errorCallback('Error: Request failed with status code ' + xhr.status);
            }
        }

        error (event, xhr) {
            console.log(event, xhr);
            this.errorCallback(xhr);
        }

        end (event, xhr) {
            this.endCallback(xhr);
        }

        then (func) {
            this.loadCallback = func;
            return this;
        }

        catch (func) {
            this.errorCallback = func;
            return this;
        }

        finally (func) {
            this.endCallback = func;
            return this;
        }

    }

    let publicMethod = {
        open: (method, url, formData) => {
            return new Client().run(method, url, formData);
        },
        get: (url, formData) => {
            return new Client().run('get', url, formData);
        },
        post: (url, formData) => {
            return new Client().run('post', url, formData);
        },
        patch: (url, formData) => {
            return new Client().run('patch', url, formData);
        },
        delete: (url, formData) => {
            return new Client().run('delete', url, formData);
        }
    }

    return publicMethod

})();

export { axios, xhttp };
