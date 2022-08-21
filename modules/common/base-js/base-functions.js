/**
 * Creates link between original element and plugin/library instance.
 * @param element original element
 * @param key plugin/library identifier
 * @param obj plugin/library instance
 */
const dataStorage = {
    _storage: new WeakMap(),
    put: function (element, key, obj) {
        if (!this._storage.has(element)) {
            this._storage.set(element, new Map());
        }
        this._storage.get(element).set(key, obj);
    },
    get: function (element, key) {
        return this._storage.get(element).get(key);
    },
    has: function (element, key) {
        return this._storage.has(element) && this._storage.get(element).has(key);
    },
    remove: function (element, key) {
        var ret = this._storage.get(element).delete(key);
        if (!this._storage.get(element).size === 0) {
            this._storage.delete(element);
        }
        return ret;
    }
}
/**
 * Ads event listener to element(s)
 * 
 * @param listenTo element
 * @param events events, names divided by comma
 * @param childOrCallback child elements for events or callback function
 * @param callback if this param provided, it will be used as callback function
 */
const on = function (listenTo, events, childOrCallback, callback) {

    let elements;

    if (typeof listenTo == 'object') {
        //if event base is document
        elements = [document];
    } else {
        elements = Array.from(document.querySelectorAll(listenTo));
    }

    let eventNames = events.split(' ');

    if (typeof childOrCallback == 'function') {
        //fourth param not provided
        let cb = childOrCallback;
        elements.forEach(function (item) {
            eventNames.forEach(function (eventName) {
                item.addEventListener(eventName, function (event) {
                    cb(item, event)
                })
            })
        })
    } else {
        //fourth param provided, typically for newly created elements with document events
        elements.forEach(function (item) {
            eventNames.forEach(function (event) {
                item.addEventListener(event, function (event) {
                    if (event.target.matches(childOrCallback)) {
                        callback(event.target, event);
                    }
                })
            })
        })
    }

}

/**
 * Event listeners with additional data transfer
 * 
 * @param listenTo 
 * @param events 
 * @param callback 
 * @param data 
 */
const onWithData = function(listenTo, events, callback, data) {
    let eventNames = events.split(' ');
    eventNames.forEach(function (event) {
        document.querySelector(listenTo).addEventListener(event, function (event) {
                callback(event.target, event, data);
        })
    })
}

/**
 * Shows selected element(s) with css display attribute
 * 
 * @param element 
 * @param displayType 
 */
const show = function (element, displayType = 'block') {
    let elements = Array.from(document.querySelectorAll(element));
    elements.forEach(function (item) {
        item.style.display = displayType;
    })
}

/**
 * Hides element(s)
 * 
 * @param element 
 */
const hide = function (element) {
    let elements = Array.from(document.querySelectorAll(element));
    elements.forEach(function (item) {
        item.style.display = 'none';
    })
}

/**
 * Adds css class to element(s)
 * 
 * @param element 
 * @param className 
 */
const addClass = function (element, className) {
    
    let elements;

    //You can pass element, node list or query selector string 
    if (typeof element == 'object') {
        if (element instanceof Element) {
            elements = [element];
        } else if (element instanceof NodeList) {
            elements = Array.from(element);
        }
    } else {
        elements = Array.from(document.querySelectorAll(element));
    }
    elements.forEach(function (item) {
        item.classList.add(className);
    })
}

/**
 * Removes css class from element(s)
 * 
 * @param element 
 * @param className 
 */
const removeClass = function (element, className) {
    
    let elements;

    //You can pass element, node list or query selector string
    if (typeof element == 'object') {
        if (element instanceof Element) {
            elements = [element];
        } else if (element instanceof NodeList) {
            elements = Array.from(element);
        }
    } else {
        elements = Array.from(document.querySelectorAll(element));
    }
    elements.forEach(function (item) {
        item.classList.remove(className);
    })
}

/**
 * Checks if element has css class
 * 
 * @param element 
 * @param className 
 * @returns boolean
 */

const hasClass = function (element, className) {

    //You can pass element or query selector string
    if (typeof element != 'object' ) {
        element = document.querySelector(element);
    }
    
    return element.classList.contains(className);
}

/**
 * Sets multiple css styles for element(s)
 * 
 * @param element 
 * @param styles 
 */
const setElementStyles = function (element, styles) {
    element = Array.from(document.querySelectorAll(element));
    element.forEach(function (item) {
        Object.assign(item.style, styles)
    })
}

/**
 * Gets propper scrollHeight
 * 
 * @returns 
 */
const scrollHeight = () => Math.max(
    document.body.scrollHeight, document.documentElement.scrollHeight,
    document.body.offsetHeight, document.documentElement.offsetHeight,
    document.body.clientHeight, document.documentElement.clientHeight
);

/**
 * Processes collection of input values
 * @param mandatories fields that must have value
 * @param optionals fields with non mandatory value
 * @param throwErrors if true, the warning message overlay will show
 */
class processInputs {

    mandatories = [];
    optionals = [];
    throwErrors = true;

    process() {

        let errors = false;
        let errorMessage = '';

        let inputsFields = [];

        let mandatories = this.mandatories;
        let optionals = this.optionals;
        let throwErrors = this.throwErrors;

        if (mandatories.length) {

            inputsFields = Array.from(document.querySelectorAll(mandatories));

            //loop through fields, if empty value mark it
            inputsFields.forEach(function (element, index) {
                if (element) {
                    let val = element.value;
                    if (val == null) val = '';
                    if (val.trim() == '') {
                        element.closest('.js_parent').querySelector('.this_element_name').classList.add('red_error');
                        element.classList.add('red_error');
                        errors = true;
                        errorMessage += '<p>Je třeba vyplnit "' + element.closest('.js_parent').querySelector('.this_element_name').textContent + '"</p>';
                    }
                }
            })

            if (errors) {
                //shows error message overlay
                if (throwErrors) {
                    let errorElement = document.querySelector('.general_error');
                    errorElement.querySelector('.error_is').innerHTML = errorMessage;
                    errorElement.style.display = 'block';
                    TweenMax.to([document.documentElement, document.body], 1, { scrollTop: getOffset(document.querySelector('.red_error')).top })
                }
                //stop execution if some of mandatory field empty
                return false;
            }
        }

        if (optionals.length) {
            //concat mandatories with optionals
            inputsFields = inputsFields.concat(Array.from(document.querySelectorAll(optionals)));
        }

        //due to fetch api FormData will be used instead of json
        let data = new FormData();

        inputsFields.forEach(function (element, index) {

            if (element) {

                let key = element.id;
                let val = 0;

                //datepicker plugin instances
                if (dataStorage.has(element, 'Datepick')) {

                    let date = new Date(dataStorage.get(element, 'Datepick').getDate('mm/dd/yyyy'));

                    //set start date to beginning of the day and opposite
                    if (element.classList.contains('from')) {
                        date.setHours(0, 0, 0, 0);
                        val = Math.ceil(date.getTime() / 1000);
                    } else if (element.classList.contains('to')) {
                        date.setHours(23, 59, 59, 999);
                        val = Math.floor(date.getTime() / 1000);
                    } else {
                        date.setHours(0, 0, 0, 0);
                        val = Math.ceil(date.getTime() / 1000);
                    }
                }
                else {
                    //get value for checkboxes
                    if (element.matches('[type="checkbox"]')) {
                        val = Number(element.checked)
                    } else {
                        val = element.value.trim();
                    }
                }

                data.append(key, val);
            }
        })

        return data;
    }
}

/**
 * Calculates element offset
 * 
 * @param el 
 * @returns offset object
 */
const getOffset = function (el) {
    const box = el.getBoundingClientRect();
    return {
        top: box.top + window.pageYOffset - document.documentElement.clientTop,
        left: box.left + window.pageXOffset - document.documentElement.clientLeft
    };
}

/**
 * Extension for Date class.
 */
class DateHelper extends Date {

    constructor(inputDate) {
        super(inputDate);
        this.inputDate = inputDate;
    }

    addDays(days) {
        var result = new Date(this.inputDate);
        result.setDate(result.getDate() + days);
        return result;
    }
}

/**
 * Helper for Tooltip plugin.
 * Assigns multiple tooltips from params
 * 
 * @param tooltipElements Map class instance with params [element, title]
 * @param tooltipOptions config object
 */
const tooltipHelper = function (tooltipElements, tooltipOptions) {
    tooltipElements.forEach(function (value, key) {
        const elements = Array.from(document.querySelectorAll(key));
        elements.forEach(function (element, index) {
            tooltipOptions.title = value;
            new Tooltip(element, tooltipOptions);
        })
    })
}

/**
 * Creates form data from object with recursion.
 * Useful when multiple leveled objects.
 * 
 * @param obj object with key/value pairs
 * @returns FormData instance
 */
class createFormData {
    constructor(obj) {

        this.formData = new FormData();

        this.createFormData = function (obj, subKeyStr = '') {
            for (let i in obj) {
                let value = obj[i];
                let subKeyStrTrans = subKeyStr ? subKeyStr + '[' + i + ']' : i;

                if (typeof (value) === 'string' || typeof (value) === 'number') {

                    this.formData.append(subKeyStrTrans, value);

                } else if (typeof (value) === 'object') {

                    this.createFormData(value, subKeyStrTrans);

                }
            }
        };

        this.createFormData(obj);

        return this.formData;
    }
}

export { getOffset, processInputs, on, onWithData, show, hide, setElementStyles, scrollHeight, DateHelper, tooltipHelper, createFormData, dataStorage, addClass, removeClass, hasClass }