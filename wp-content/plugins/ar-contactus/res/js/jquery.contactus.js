"use strict";

(function($){
    function ArContactUs(element, options){
        this._initialized = false;
        this.settings = null;
        this.popups = [];
        this.options = $.extend({}, ArContactUs.Defaults, options);
        this.$element = $(element);
        this.x = 0;
        this.y = 0;
        this._interval;
        this._timeout;
        this._animation = false;
        this._menuOpened = false;
        this._popupOpened = false;
        this._callbackOpened = false;
        this._formOpened = false;
        this._wellcomeMessagesDone = false;
        this.countdown = null;
        this.svgPath = null;
        this.svgSteps = [],
        this.svgPathOpen = null;
        this.svgInitialPath = null;
        this.isAnimating = false;
        this.init();
    };
    ArContactUs.Defaults = {
        online: null,
        activated: false,
        pluginVersion: '2.3.2',
        wordpressPluginVersion: false,
        align: 'right',
        mode: 'regular',
        countdown: 0,
        drag: false,
        buttonText: 'Contact us',
        buttonSize: 'large',
        buttonIconSize: 24,
        menuSize: 'normal',
        buttonIcon: '<svg width="20" height="20" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g transform="translate(-825 -308)"><g><path transform="translate(825 308)" fill="#FFFFFF" d="M 19 4L 17 4L 17 13L 4 13L 4 15C 4 15.55 4.45 16 5 16L 16 16L 20 20L 20 5C 20 4.45 19.55 4 19 4ZM 15 10L 15 1C 15 0.45 14.55 0 14 0L 1 0C 0.45 0 0 0.45 0 1L 0 15L 4 11L 14 11C 14.55 11 15 10.55 15 10Z"/></g></g></svg>',
        ajaxUrl: 'server.php',
        action: 'callback',
        phonePlaceholder: '+X-XXX-XXX-XX-XX',
        callbackSubmitText: 'Waiting for call',
        reCaptcha: false,
        reCaptchaAction: 'callbackRequest',
        reCaptchaKey: '',
        errorMessage: 'Connection error. Please try again.',
        callProcessText: 'We are calling you to phone',
        callSuccessText: 'Thank you.<br>We are call you back soon.',
        showMenuHeader: false,
        menuHeaderText: 'How would you like to contact us?',
        menuSubheaderText: '',
        menuHeaderLayout: 'icon-center',
        layout: 'default',
        itemsHeader: 'Start chat with:',
        menuHeaderIcon: null,
        menuHeaderTextAlign: 'center',
        menuHeaderOnline: true,
        showHeaderCloseBtn: true,
        menuInAnimationClass: 'show-messageners-block',
        menuOutAnimationClass: '',
        eaderCloseBtnBgColor: '#787878',
        eaderCloseBtnColor: '#FFFFFF',
        items: [],
        itemsIconType: 'rounded',
        iconsAnimationSpeed: 800,
        iconsAnimationPause: 2000,
        promptPosition: 'side',
        style: null,
        itemsAnimation: null,
        popupAnimation: 'scale',
        forms: {},
        theme: '#000000',
        subMenuHeaderBackground: '#FFFFFF',
        subMenuHeaderColor: '#FFFFFF',
        callbackFormText: 'Please enter your phone number<br>and we call you back soon',
        closeIcon: '<svg width="12" height="13" viewBox="0 0 14 14" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g transform="translate(-4087 108)"><g><path transform="translate(4087 -108)" fill="currentColor" d="M 14 1.41L 12.59 0L 7 5.59L 1.41 0L 0 1.41L 5.59 7L 0 12.59L 1.41 14L 7 8.41L 12.59 14L 14 12.59L 8.41 7L 14 1.41Z"></path></g></g></svg>',
        callbackStateIcon: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M493.4 24.6l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-36 76.7-98.9 140.5-177.2 177.2l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48C3.9 366.5-2 378.1.6 389.4l24 104C27.1 504.2 36.7 512 48 512c256.1 0 464-207.5 464-464 0-11.2-7.7-20.9-18.6-23.4z"></path></svg>'
    };
    ArContactUs.prototype.init = function(){
        if (this._initialized){
            return false;
        }
        this.destroy();
        this.settings = $.extend({}, this.options);
        this.settings.forms.dynamic_form = {
            header: ''
        };
        this.$element.addClass('arcontactus-widget').addClass('arcontactus-message').addClass('layout-' + this.settings.layout);
        if (this.settings.style == 'elastic') {
            this.$element.addClass('arcu-elastic');
        }
        if (this.settings.style == 'bubble') {
            this.$element.addClass('arcu-bubble');
        }
        if ((this.settings.style == null || this.settings.style == 'popup' || this.settings.style == '') && this.settings.popupAnimation) {
            this.$element.addClass('arcu-'+this.settings.popupAnimation);
        }
        if (this.settings.align === 'left'){
            this.$element.addClass('left');
        }else{
            this.$element.addClass('right');
        }
        if (this.settings.items.length){
            this.$element.append('<!--noindex-->');
            
            if (this.settings.mode == 'regular' || this.settings.mode == 'single'){
                this._initMessengersBlock();
                if (this.settings.mode == 'single') {
                    var $a = this.$element.find('.messangers-list li:first-child a');
                    if ($a.attr('href')) {
                        this.$element.append($('<a>', {
                            href: $a.attr('href'),
                            target: $a.attr('target'),
                            class: 'arcu-single-mode-link'
                        }));
                    }
                }
            }
            if (this.popups.length) {
                this._initPopups();
            }
            this._initMessageButton();
            this._initForms();
            this._initPrompt();
            this._initEvents();
            var $this = this;
            setTimeout(function(){
                $this.startAnimation();
            }, this.settings.iconsAnimationPause? this.settings.iconsAnimationPause : 2000);
            if (this.settings.online !== null) {
                var $onlineBadge = $('<div>', {
                    class: 'arcu-online-badge ' + (this.settings.online === true? 'online' : 'offline')
                });
                this.$element.append($onlineBadge);
            }
            this.$element.append('<!--/noindex-->');
            this.$element.addClass('active');
        }else{
            console.info('jquery.contactus:no items');
        }
        if (this.settings.style == 'elastic' || this.settings.style == 'bubble') {
            var morphEl = document.getElementById('arcu-morph-shape');
            var s = Snap(morphEl.querySelector('svg'));
            this.svgPath = s.select('path');
            this.svgPathOpen = morphEl.getAttribute('data-morph-open');
            this.svgInitialPath = this.svgPath.attr('d');
            this.svgSteps = this.svgPathOpen.split(';');
            this.svgStepsTotal = this.svgSteps.length;
        }
        this._initialized = true;
        this.$element.trigger('arcontactus.init');
    };
    ArContactUs.prototype.destroy = function(){
        if (!this._initialized){
            return false;
        }
        this.stopAnimation(false);
        this._removeEvents();
        this.$element.find('.arcontactus-message-button').unbind();
        this.$element.html('');
        this.$element.removeClass();
        this.$element.unbind().removeData('ar.contactus');
        this._initialized = false;
        this.$element.trigger('arcontactus.destroy');
    };
    ArContactUs.prototype._initForms = function(){
        var $plugin = this;
        var $container = $('<div>', {
            class: 'arcu-forms-container'
        });
        var $close = $('<div>', {
            class: 'arcu-close',
            style: 'background-color:' + this.settings.theme + '; color: #FFFFFF'
        });
        $close.append(this.settings.closeIcon);
        $container.append($close);
        $.each($plugin.settings.forms, function(i){
            var form = this;
            
            if (form.icon) {
                var $formIcon = $('<div>', {
                    id: 'form-icon-' + i,
                    class: 'form-icon'
                });
                $formIcon.append(form.icon);
                $plugin.$element.find('.arcontactus-message-button').append($formIcon);
            }
            
            var $formContainer = $('<div>', {
                class: 'arcu-form-container',
                id: 'arcu-form-' + i
            });
            if (typeof form.action !== 'undefined'){
                var $form = $('<form>', {
                    action: form.action,
                    method: 'POST',
                    class: 'arcu-form',
                    'data-id': i
                });
            } else {
                var $form = $('<div>', {
                    class: 'arcu-form'
                });
            }
            if (typeof form.header == 'string') {
                var $header = $('<div>', {
                    class: 'arcu-form-header',
                    style: $plugin._backgroundStyle()
                });
                $header.html(form.header);
                $form.append($header);
            }else if (typeof form.header == 'object'){
                var $header = $('<div>', {
                    class: 'arcu-form-header ' + form.header.layout,
                    style: $plugin._backgroundStyle()
                });
                var $headerContent = $('<div>', {
                    class: 'arcu-form-header-content'
                });
                $headerContent.html(form.header.content);
                var $headerIcon = $('<div>', {
                    class: 'arcu-form-header-icon'
                });
                $headerIcon.html(form.header.icon);
                $header.append($headerIcon);
                $header.append($headerContent);
                $form.append($header);
            }
            $plugin._initFormFields(form, $form);
            $plugin._initFormButtons(form, $form);
            
            $formContainer.append($form);
            if (typeof form.success == 'string') {
                var $formSuccess = $('<div>', {
                    class: 'arcu-form-success'
                });
                var $formSuccessContent = $('<div>');
                $formSuccessContent.html(form.success);
                $formSuccess.append($formSuccessContent);
                $formContainer.append($formSuccess);
            }
            if (typeof form.error == 'string') {
                var $formError = $('<div>', {
                    class: 'arcu-form-error'
                });
                var $formErrorContent = $('<div>');
                $formErrorContent.html(form.error);
                $formError.append($formErrorContent);
                $formContainer.append($formError);
            }
            $container.append($formContainer);
        });
        
        this.$element.append($container);
    },
    ArContactUs.prototype._initFormButtons = function(form, $formContainer){
        var $this = this;
        $.each(form.buttons , function(i){
            var button = form.buttons[i];
            
            var $buttonContainer = $('<div>', {
                class: 'arcu-form-group arcu-form-button',
            });
            var buttonClass = '';
            if (typeof button.class != 'undefined') {
                buttonClass = button.class;
            }
            if (['button', 'submit'].indexOf(button.type) !== -1){
                var $button = $('<button>', {
                    id: 'arcu-button-' + button.id,
                    class: 'arcu-button ' + buttonClass,
                    type: button.type,
                    style: $this._backgroundStyle()
                });
            } else if(button.type == 'a') {
                var $button = $('<a>', {
                    id: 'arcu-button-' + button.id,
                    class: 'arcu-button ' + buttonClass,
                    href: button.href,
                    type: button.type,
                    style: $this._backgroundStyle()
                });
            }
            $button.text(button.label);
            
            $buttonContainer.append($button);
            
            $formContainer.append($buttonContainer);
        });
    },
    ArContactUs.prototype._initFormFields = function(form, $formContainer){
        $.each(form.fields, function(i){
            var field = form.fields[i];
            
            var $inputContainer = $('<div>', {
                class: 'arcu-form-group arcu-form-group-type-' + field.type + ' arcu-form-group-' + field.name + (field.required? ' arcu-form-group-required' : ''),
            });
            var input = '<input>';
            switch(field.type){
                case 'textarea':
                    input = '<textarea>';
                    break;
                case 'dropdown':
                    input = '<select>';
                    break;
            }
            if (field.label){
                var $inputLabel = $('<div>', {
                    class: 'arcu-form-label'
                });
                var $label = $('<label>', {
                    for: 'arcu-field-' + form.id + '-' + field.name
                });
                $label.html(field.label);
                $inputLabel.append($label);
                $inputContainer.append($inputLabel);
            }
            var $input = $(input, {
                name: field.name,
                class: 'arcu-form-field arcu-field-' + field.name,
                required: field.required,
                type: field.type == 'dropdown'? null : field.type,
                id: 'arcu-field-' + form.id + '-' + field.name,
                value: field.value? field.value : '',
            });
            $input.attr('placeholder', field.placeholder);
            if (typeof field.maxlength != 'undefined') {
                $input.attr('maxlength', field.maxlength);
            }
            if (field.type == 'dropdown'){
                $.each(field.values, function(i){
                    var val = field.values[i];
                    var lbl = field.values[i];
                    if (typeof field.values[i] == 'object'){
                        var val = field.values[i].value;
                        var lbl = field.values[i].label;
                    }
                    var $option = $('<option>', {
                        value: val
                    });
                    $option.text(lbl);
                    $input.append($option);
                });
            }
            $inputContainer.append($input);
            $inputContainer.append($('<div>', {
                class: 'arcu-form-field-errors'
            }));
            $formContainer.append($inputContainer);
        });
    },
    ArContactUs.prototype._initPopups = function(){
        var $this = this;
        var $container = $('<div>', {
            class: 'popups-block arcuAnimated'
        });
        var $popupListContainer = $('<div>', {
            class: 'popups-list-container'
        });
        $.each(this.popups, function(){
            var $popup = $('<div>', {
                class: 'arcu-popup',
                id: 'arcu-popup-' + this.id
            });
            var $header = $('<div>', {
                class: 'arcu-popup-header',
                style: ($this.settings.theme? ('background-color:' + $this.settings.theme) : null)
            });
            var $close = $('<div>', {
                class: 'arcu-popup-close',
                style: ($this.settings.theme? ('background-color:' + $this.settings.theme) : null)
            });
            var $back = $('<div>', {
                class: 'arcu-popup-back',
                style: ($this.settings.theme? ('background-color:' + $this.settings.theme) : null)
            });
            $close.append($this.settings.closeIcon);
            $back.append('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M231.293 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L70.393 256 251.092 74.87c4.686-4.686 4.686-12.284 0-16.971L231.293 38.1c-4.686-4.686-12.284-4.686-16.971 0L4.908 247.515c-4.686 4.686-4.686 12.284 0 16.971L214.322 473.9c4.687 4.686 12.285 4.686 16.971-.001z" class=""></path></svg>');
            
            $header.text(this.title);
            $header.append($close);
            $header.append($back);
            var $content = $('<div>', {
                class: 'arcu-popup-content'
            });
            $content.html(this.popupContent);
            
            $popup.append($header);
            $popup.append($content);
            $popupListContainer.append($popup);
        });
        $container.append($popupListContainer);
        this.$element.append($container);
    },
    ArContactUs.prototype._initMessengersBlock = function(){
        var $container = $('<div>', {
            class: 'messangers-block arcuAnimated'
        });
        var $menuListContainer = $('<div>', {
            class: 'messangers-list-container'
        });
        if (this.settings.layout == 'personal') {
            var $itemsHeader = $('<div>', {
                class: 'arcu-items-header'
            });
            $itemsHeader.html(this.settings.itemsHeader);
            
            var $wellcomeMessages = $('<div>', {
                class: 'arcu-wellcome'
            });
            
            $menuListContainer.append($wellcomeMessages);
            $menuListContainer.append($itemsHeader);
        }
        var $menuContainer = $('<ul>', {
            class: 'messangers-list'
        });
        if (this.settings.itemsAnimation){
            $menuContainer.addClass('arcu-'+this.settings.itemsAnimation);
        }
        if (this.settings.menuSize === 'normal' || this.settings.menuSize === 'large'){
            $container.addClass('lg');
        }
        if (this.settings.menuSize === 'small'){
            $container.addClass('sm');
        }
        this._appendMessengerIcons($menuContainer, this.settings.items);
        if (this.settings.showMenuHeader){
            var $header = $('<div>', {
                class: 'arcu-menu-header arcu-' + this.settings.menuHeaderLayout,
                style: (this.settings.theme? ('background-color:' + this.settings.theme) : null)
            });
            var $headerContent = $('<div>', {
                class: 'arcu-menu-header-content arcu-text-' + this.settings.menuHeaderTextAlign
            });
            
            $headerContent.html(this.settings.menuHeaderText);
            if (this.settings.menuHeaderIcon) {
                var $headerIcon = $('<div>', {
                    class: 'arcu-header-icon'
                });
                if (this.settings.menuHeaderIcon.match(/^https?:\/\//)){
                    $headerIcon.css('background-image', 'url(' + this.settings.menuHeaderIcon + ')').addClass('arcu-bg-image');
                } else {
                    $headerIcon.append(this.settings.menuHeaderIcon);
                }
                if (this.settings.menuHeaderOnline !== null) {
                    var $headerOnlineBadge = $('<div>', {
                        class: 'arcu-online-badge ' + (this.settings.menuHeaderOnline? 'online' : 'offline'),
                        style: 'border-color: ' + this.settings.theme
                    });
                    $headerIcon.append($headerOnlineBadge);
                }
                $header.append($headerIcon);
            }
            $header.append($headerContent);
            if (this.settings.menuSubheaderText) {
                var $subheaderContent = $('<div>', {
                    class: 'arcu-menu-subheader arcu-text-' + this.settings.menuHeaderTextAlign
                });
                $subheaderContent.html(this.settings.menuSubheaderText);
                $header.append($subheaderContent);
            }
            if (this.settings.showHeaderCloseBtn){
                var $closeBtn = $('<div>', {
                    class: 'arcu-header-close',
                    style: 'color:' + this.settings.headerCloseBtnColor + '; background:' + this.settings.headerCloseBtnBgColor
                });
                
                $closeBtn.append(this.settings.closeIcon);
                $header.append($closeBtn);
            }
            $container.append($header);
            $container.addClass('has-header');
        }
        if (this.settings.itemsIconType == 'rounded'){
            $menuContainer.addClass('rounded-items');
        }else{
            $menuContainer.addClass('not-rounded-items');
        }
        $menuListContainer.append($menuContainer);
        $container.append($menuListContainer);
        if (this.settings.style == 'elastic') {
            var $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100 800" preserveAspectRatio="none"><path d="M-1,0h101c0,0-97.833,153.603-97.833,396.167C2.167,627.579,100,800,100,800H-1V0z"/></svg>';
            var $svgContainer = $('<div>', {
                class: 'arcu-morph-shape',
                id: 'arcu-morph-shape',
                'data-morph-open': 'M-1,0h101c0,0,0-1,0,395c0,404,0,405,0,405H-1V0z'
            });
            $svgContainer.append($svg);
            $container.append($svgContainer);
        }else if (this.settings.style == 'bubble') {
            var $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 100 800" preserveAspectRatio="none"><path d="M-7.312,0H0c0,0,0,113.839,0,400c0,264.506,0,400,0,400h-7.312V0z"></path><defs></defs></svg>';
            var $svgContainer = $('<div>', {
                class: 'arcu-morph-shape',
                id: 'arcu-morph-shape',
                'data-morph-open': 'M-7.312,0H15c0,0,66,113.339,66,399.5C81,664.006,15,800,15,800H-7.312V0z;M-7.312,0H100c0,0,0,113.839,0,400c0,264.506,0,400,0,400H-7.312V0z'
            });
            $svgContainer.append($svg);
            $container.append($svgContainer);
        }
        this.$element.append($container);
    };
    ArContactUs.prototype._appendMessengerIcons = function($container, items){
        var $plugin = this;
        $.each(items, function(i){
            var $li = $('<li>', {});
            if(this.href == '_popup'){
                $plugin.popups.push(this);
                var $item = $('<div>', {
                    class: 'messanger arcu-popup-link ' + (this.class? this.class : ''),
                    title: this.title,
                    'data-id': (this.id? this.id : null),
                });
            }else{
                var $item = $('<a>', {
                    class: 'messanger ' + (this.class? this.class : '') + (this.addons? ' has-addon ' : ''),
                    id: (this.id? this.id : null),
                    rel: 'nofollow noopener',
                    href: this.href,
                    title: this.title,
                    target: (this.target? this.target : '_blank')
                });
                if (this.onClick){
                    var $this = this;
                    $item.on('click', function(e){
                        $this.onClick(e);
                    });
                }
            }
            if (this.addons){
                $.each(this.addons, function(ii){
                    var $addon = $('<a>', {
                        href: this.href,
                        title: (this.title? this.title : null),
                        target: (this.target? this.target : '_blank'),
                        class: (this.class? this.class : 'arcu-addon'),
                        style: (this.color? ('color:' + this.color) : null) + '; background-color: transparent'
                    });
                    if (this.icon) {
                        if (this.icon.indexOf('<') === 0){
                            $addon.append(this.icon);
                        }else if(this.icon.indexOf('<') === -1){
                            var $icon = $('<img>', {
                                src: this.icon
                            });
                            $addon.append($icon);
                        }
                    }
                    if (this.onClick){
                        var $this = this;
                        $addon.on('click', function(e){
                            return $this.onClick(e);
                        });
                    }
                    $item.append($addon);
                });
            }
            if ($plugin.settings.itemsIconType == 'rounded'){
                if (this.noContainer){
                    var $icon = $('<span>', {
                        style: ((this.color)? ('color:' + this.color + '; fill: ' + this.color) : null),
                        class: 'no-container'
                    });
                }else{
                    var $icon = $('<span>', {
                        style: ((this.color && !this.noContainer)? ('background-color:' + this.color) : null)
                    });
                }
            }else{
                if (this.noContainer){
                    var $icon = $('<span>', {
                        style: ((this.color)? ('color:' + this.color + '; fill: ' + this.color) : null),
                        class: 'no-container'
                    });
                }else{
                    var $icon = $('<span>', {
                        style: ((this.color && !this.noContainer)? ('color:' + this.color) : null) + '; background-color: transparent'
                    });
                }
            }
            if (typeof this.online !== 'undefined' && this.online !== null) {
                var $onlineBadge = $('<div>', {
                    class: 'arcu-online-badge ' + (this.online === true? 'online' : 'offline')
                });
                $icon.append($onlineBadge);
            }
            $icon.append(this.icon);
            $item.append($icon);
            var $label = $('<div>', {
                class: 'arcu-item-label'
            });
            var $title = $('<div>', {
                class: 'arcu-item-title'
            });
            $title.text(this.title);
            $label.append($title);
            if (typeof this.subTitle != 'undefined' && this.subTitle){
                var $subTitle = $('<div>', {
                    class: 'arcu-item-subtitle'
                });
                $subTitle.text(this.subTitle);
                $label.append($subTitle);
            }
            
            $item.append($label);
            $li.append($item);
            $container.append($li);
            if (this.items) {
                var $item = this;
                var itemId = this.id;
                var $subMenuHeader = $('<div>', {
                    class: 'arcu-submenu-header',
                    style: 'background-color:' + $plugin.settings.subMenuHeaderBackground + '; color: ' + $item.subMenuHeaderIconColor
                });
                var $subMenuTitle = $('<div>', {
                    class: 'arcu-submenu-title arcu-text-' + this.subMenuHeaderTextAlign,
                    style: 'color:' + $plugin.settings.subMenuHeaderColor
                });
                if (this.subMenuHeader) {
                    $subMenuTitle.text(this.subMenuHeader);
                } else {
                    $subMenuTitle.text(this.title);
                }
                var $subMenuBack = $('<div>', {
                    class: 'arcu-submenu-back',
                    style: 'color:' + $plugin.settings.subMenuHeaderColor + '; fill: ' + $plugin.settings.subMenuHeaderColor
                });
                $subMenuBack.html('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M231.293 473.899l19.799-19.799c4.686-4.686 4.686-12.284 0-16.971L70.393 256 251.092 74.87c4.686-4.686 4.686-12.284 0-16.971L231.293 38.1c-4.686-4.686-12.284-4.686-16.971 0L4.908 247.515c-4.686 4.686-4.686 12.284 0 16.971L214.322 473.9c4.687 4.686 12.285 4.686 16.971-.001z" class=""></path></svg>')
                $subMenuBack.attr('data-rel', itemId);
                
                $subMenuBack.on('click', function(){
                    $plugin.hideSubmenu({id: '#' + itemId});
                });
                
                $subMenuHeader.append($subMenuBack);
                if (this.subMenuHeaderIcon) {
                    $subMenuHeader.append(this.subMenuHeaderIcon);
                }
                $subMenuHeader.append($subMenuTitle);
                
                var $div = $('<div>', {
                    class: 'arcu-submenu-container'
                });
                var $ul = $('<ul>', {
                    class: 'arcu-submenu'
                });
                $div.append($subMenuHeader);
                $div.append($ul);
                $plugin._appendMessengerIcons($ul, this.items);
                
                $li.append($div);
            }
        });
    };
    ArContactUs.prototype._initMessageButton = function(){
        var $this = this;
        var $container = $('<div>', {
            class: 'arcontactus-message-button',
            style: this._backgroundStyle()
        });
        if (this.settings.buttonSize === 'large'){
            this.$element.addClass('lg');
        }
        if (this.settings.buttonSize === 'huge'){
            this.$element.addClass('hg');
        }
        if (this.settings.buttonSize === 'medium'){
            this.$element.addClass('md');
        }
        if (this.settings.buttonSize === 'small'){
            this.$element.addClass('sm');
        }
        var $static = $('<div>', {
            class: 'static'
        });
        var $staticContent = $('<div>', {
            class: 'img-' + this.settings.buttonIconSize
        });
        $staticContent.append(this.settings.buttonIcon);
        if (this.settings.buttonText !== false){
            $staticContent.append('<p>' + this.settings.buttonText + '</p>');
        }else{
            $container.addClass('no-text');
        }
        $static.append($staticContent);
        var $callBackState = $('<div>', {
            class: 'callback-state',
            style: $this._colorStyle()
        });
        
        $callBackState.append(this.settings.callbackStateIcon);
        
        var $icons = $('<div>', {
            class: 'icons hide'
        });
        
        var $iconsLine = $('<div>', {
            class: 'icons-line'
        });
        
        $.each(this.settings.items, function(i){
            if (this.includeIconToSlider) {
                var $icon = $('<span>', {
                    style: $this._colorStyle()
                });
                $icon.append(this.icon);
                $iconsLine.append($icon);
            }
        });
        
        $icons.append($iconsLine);
        
        
        var $close = $('<div>', {
            class: 'arcontactus-close'
        });
        
        $close.append(this.settings.closeIcon);
        
        var $pulsation = $('<div>', {
            class: 'pulsation',
            style: $this._backgroundStyle()
        });
        
        var $pulsation2 = $('<div>', {
            class: 'pulsation',
            style: $this._backgroundStyle()
        });
        
        $container.append($static).append($callBackState).append($icons).append($close).append($pulsation).append($pulsation2);
        
        this.$element.append($container);
    };
    
    ArContactUs.prototype._initPrompt = function(){
        var $container = $('<div>', {
            class: 'arcontactus-prompt arcu-prompt-' + this.settings.promptPosition
        });
        var $close = $('<div>', {
            class: 'arcontactus-prompt-close',
            style: this._backgroundStyle() + '; color: #FFFFFF'
        });
        $close.append(this.settings.closeIcon);
        
        var $inner = $('<div>', {
            class: 'arcontactus-prompt-inner',
        });
        
        $container.append($close).append($inner);
        
        this.$element.append($container);
    };
    
    ArContactUs.prototype._initEvents = function(){
        var $el = this.$element;
        var $this = this;
        $el.find('.arcontactus-message-button').on('mousedown', function(e) {
            $this.x = e.pageX;
            $this.y = e.pageY;
        }).on('mouseup', function(e) {
            if (($this.settings.drag && e.pageX === $this.x && e.pageY === $this.y) || !$this.settings.drag) {
                if ($this.settings.mode == 'regular'){
                    if (!$this._menuOpened && !$this._popupOpened && !$this._callbackOpened && !$this._formOpened) {
                        $this.openMenu();
                    }else{
                        if ($this._menuOpened){
                            $this.closeMenu();
                        }
                        if ($this._popupOpened){
                            $this.closePopup();
                        }
                    }
                }else if($this.settings.mode == 'single'){
                    var $a = $el.find('.messangers-list li:first-child a');
                    if ($a.attr('href')) {
                        // do nothing
                    } else {
                        $a.click();
                    }
                }else{
                    $this.showForm('callback');
                }
                e.preventDefault();
            }
        });
        if (this.settings.drag){
            $el.draggable();
            $el.get(0).addEventListener('touchmove', function(event) {
                var touch = event.targetTouches[0];
                // Place element where the finger is
                $el.get(0).style.left = touch.pageX-25 + 'px';
                $el.get(0).style.top = touch.pageY-25 + 'px';
                event.preventDefault();
            }, false);
        }
        $(document).on('click', function(e) {
            $this.closeMenu();
            $this.closePopup();
        });
        $el.on('click', function(e){
            e.stopPropagation(); 
        });
        $el.find('.call-back').on('click', function() {
            $this.openCallbackPopup();
        });
        $el.find('.arcu-popup-link').on('click', function() {
            var id = $(this).data('id');
            $this.openPopup(id);
        });
        $el.find('.arcu-header-close').on('click', function() {
            $this.closeMenu();
        });
        $el.find('.arcu-popup-close').on('click', function() {
            $this.closePopup();
        });
        $el.find('.arcu-popup-back').on('click', function() {
            $this.closePopup();
            $this.openMenu();
        });
        
        $el.find('.arcu-close').on('click', function() {
            if ($this.countdown != null) {
                clearInterval($this.countdown);
                $this.countdown = null;
            }
            $this.hideForm();
        });
        $el.find('.arcontactus-prompt-close').on('click', function() {
            $this.hidePrompt();
        });
        $el.find('form').on('submit', function(event) {
            event.preventDefault();
            var $form = $(this);
            $form.parent().addClass('ar-loading');
            if ($this.settings.reCaptcha) {
                grecaptcha.execute($this.settings.reCaptchaKey, {
                    action: $this.settings.reCaptchaAction
                }).then(function(token) {
                    $el.find('.ar-g-token').val(token);
                    $this.sendFormData($form);
                });
            }else{
                $this.sendFormData($form);
            }
        });
        setTimeout(function(){
            $this._processHash();
        },500);
        $(window).on('hashchange', function(event){
            $this._processHash();
        });
    };
    ArContactUs.prototype._removeEvents = function(){
        $(document).unbind('click');
    };
    ArContactUs.prototype._processHash = function(){
        var hash =  window.location.hash;
        var $this = this;
        switch(hash){
            case '#callback-form':
            case 'callback-form':
                $this.showForm('callback');
                break;
            case '#callback-form-close':
            case 'callback-form-close':
                $this.hideForm();
                break;
            case '#contactus-menu':
            case 'contactus-menu':
                $this.openMenu();
                break;
            case '#contactus-menu-close':
            case 'contactus-menu-close':
                $this.closeMenu();
                break;
            case '#contactus-hide':
            case 'contactus-hide':
                $this.hide();
                break;
            case '#contactus-show':
            case 'contactus-show':
                $this.show();
                break;
        }
    },
    ArContactUs.prototype._callBackCountDownMethod = function(){
        var secs = this.settings.countdown;
        var $el = this.$element;
        var $this = this;
        var ms = 60;
        $el.find('.callback-countdown-block-phone, .callback-countdown-block-timer').toggleClass('display-flex');
        this.countdown = setInterval(function() {
            ms = ms - 1;
            var fsecs = secs;
            var fms = ms;
            if (secs < 10) {
                fsecs = "0" + secs;
            }
            if (ms < 10) {
                fms = "0" + ms;
            }
            var format = fsecs + ":" + fms;
            $el.find('.callback-countdown-block-timer_timer').html(format);
            if (ms === 0 && secs === 0) {
                clearInterval($this.countdown);
                $this.countdown = null;
                $el.find('.callback-countdown-block-sorry, .callback-countdown-block-timer').toggleClass('display-flex');
            }
            if (ms === 0) {
                ms = 60;
                secs = secs - 1;
            }
        }, 20);
    };
    ArContactUs.prototype._clearFormErrors = function($form){
        $form.find('.arcu-form-group.has-error').removeClass('has-error');
    };
    ArContactUs.prototype._processFormErrors = function($form, data){
        if (data.success == 0) {
            $.each(data.errors, function(index){
                $form.find('.arcu-form-group-' + index).addClass('has-error');
                $form.find('.arcu-form-group-' + index).find('.arcu-form-field-errors').html(data.errors[index].join('<br/>'));
            });
        }
    };
    ArContactUs.prototype.sendFormData = function($form){
        var $this = this;
        var $el = $this.$element;
        $el.trigger('arcontactus.beforeSendFormData', {form: $form});
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            dataType: 'json',
            data: $form.serialize(),
            success: function(data) {
                $form.parent().removeClass('ar-loading');
                $this._clearFormErrors($form);
                if (data.success) {
                    $form.parent().find('.arcu-form-success').addClass('active');
                    $form.parent().find('.arcu-form-error').removeClass('active');
                    $el.trigger('arcontactus.successSendFormData', {form: $form, data: data});
                } else {
                    if (data.errors){
                        $this._processFormErrors($form, data);
                    }
                    $el.trigger('arcontactus.errorSendFormData', {form: $form, data: data});
                }
            },
            error: function(){
                $this._clearFormErrors($form);
                $form.parent().find('.arcu-form-success').removeClass('active');
                $form.parent().find('.arcu-form-error').addClass('active');
                $form.parent().removeClass('ar-loading');
                alert($this.settings.errorMessage);
                $el.trigger('arcontactus.errorSendFormData', {form: $form, data: null});
            }
        });
    },
    ArContactUs.prototype.show = function(){
        this.$element.addClass('active');
        this.$element.trigger('arcontactus.show');
    };
    ArContactUs.prototype.hide = function(){
        this.$element.removeClass('active');
        this.$element.trigger('arcontactus.hide');
    };
    ArContactUs.prototype.openPopup = function(id){
        this.closeMenu();
        var $el = this.$element;
        $el.find('#arcu-popup-' + id).addClass('show-messageners-block');
        if (!$el.find('#arcu-popup-' + id).hasClass('popup-opened')) {
            this.stopAnimation(false);
            $el.addClass('popup-opened');
            $el.find('#arcu-popup-' + id).addClass(this.settings.menuInAnimationClass);
            $el.find('.arcontactus-close').addClass('show-messageners-block');
            $el.find('.icons, .static').addClass('hide');
            $el.find('.pulsation').addClass('stop');
            this._popupOpened = true;
            this.$element.trigger('arcontactus.openPopup');
        }
    },
    ArContactUs.prototype.closePopup = function(){
        var $el = this.$element;
        if ($el.find('.arcu-popup').hasClass('show-messageners-block')) {
            setTimeout(function(){
                $el.removeClass('popup-opened');
            }, 150);
            $el.find('.arcu-popup').removeClass(this.settings.menuInAnimationClass).addClass(this.settings.menuOutAnimationClass);
            setTimeout(function(){
                $el.removeClass('popup-opened');
            }, 150);
            $el.find('.arcontactus-close').removeClass('show-messageners-block');
            $el.find('.icons, .static').removeClass('hide');
            $el.find('.pulsation').removeClass('stop');
            this.startAnimation();
            this._popupOpened = false;
            this.$element.trigger('arcontactus.closeMenu');
        }
    },
    ArContactUs.prototype.openMenu = function(){
        if (this.settings.mode == 'callback'){
            console.log('Widget in callback mode');
            return false;
        }
        if (this._formOpened){
            this.hideForm();
        }
        if (this.settings.style == 'elastic' || this.settings.style == 'bubble'){
            jQuery('body').addClass('arcu-show-menu').addClass('arcu-menu-' + this.settings.align);
            jQuery('body').addClass('arcu-pushed');
        }
        var $el = this.$element;
        var $this = this;
        if (!$el.find('.messangers-block').hasClass(this.settings.menuInAnimationClass)) {
            this.stopAnimation(false);
            $el.addClass('open');
            $el.find('.messangers-block').addClass(this.settings.menuInAnimationClass);
            $el.find('.arcontactus-close').addClass('show-messageners-block');
            $el.find('.icons, .static').addClass('hide');
            $el.find('.pulsation').addClass('stop');
            this._menuOpened = true;
            this.$element.trigger('arcontactus.openMenu');
        }
        if (this.settings.style == 'elastic') {
            this.svgPath.animate({
                path: this.svgPathOpen
            }, 400, mina.easeinout, function() {
                $this.isAnimating = false;
            });
        }else if(this.settings.style == 'bubble') {
            var pos = 0,
            nextStep = function( pos ) {
                if( pos > $this.svgStepsTotal - 1 ) {
                    //isAnimating = false; 
                    return;
                }
                $this.svgPath.animate({ 
                    path: $this.svgSteps[pos]
                }, pos === 0 ? 400 : 500, pos === 0 ? mina.easein : mina.elastic, function() {
                    nextStep(pos);
                });
                pos++;
            };

            nextStep(pos);
        }
    };
    ArContactUs.prototype.closeMenu = function(){
        if (this.settings.mode == 'callback'){
            console.log('Widget in callback mode');
            return false;
        }
        if (this.settings.style == 'elastic' || this.settings.style == 'bubble'){
            jQuery('body').removeClass('arcu-show-menu').removeClass('arcu-menu-' + this.settings.align);
            setTimeout(function(){
                jQuery('body').removeClass('arcu-pushed');
            }, 500);
        }
        var $el = this.$element;
        var $this = this;
        if ($el.find('.messangers-block').hasClass(this.settings.menuInAnimationClass)) {
            setTimeout(function(){
                if (!$this._formOpened){
                    $el.removeClass('open');
                }
            }, 150);
            $el.find('.messangers-block').removeClass(this.settings.menuInAnimationClass).addClass(this.settings.menuOutAnimationClass);
            setTimeout(function(){
                $el.find('.messangers-block').removeClass($this.settings.menuOutAnimationClass);
            }, 1000);
            $el.find('.arcontactus-close').removeClass('show-messageners-block');
            $el.find('.static').removeClass('hide');
            $el.find('.pulsation').removeClass('stop');
            this._menuOpened = false;
            if ($this.settings.iconsAnimationPause){
                $this._timeout = setTimeout(function(){
                    if ($this._callbackOpened || $this._menuOpened || $this._popupOpened || $this._formOpened){
                        return false;
                    }
                    $this.startAnimation();
                }, $this.settings.iconsAnimationPause);
            } else {
                this.startAnimation();
            }
            this.$element.trigger('arcontactus.closeMenu');
        }
        if (this.settings.style == 'elastic' || this.settings.style == 'bubble') {
            setTimeout(function() {
                // reset path
                $this.svgPath.attr('d', $this.svgInitialPath);
                $this.isAnimating = false; 
            }, 300);
        }
    };
    ArContactUs.prototype.toggleMenu = function(){
        var $el = this.$element;
        this.hidePrompt();
        if ($el.find('.callback-countdown-block').hasClass('display-flex')){
            return false;
        }
        if (!$el.find('.messangers-block').hasClass(this.settings.menuInAnimationClass)) {
            this.openMenu();
        }else{
            this.closeMenu();
        }
        this.$element.trigger('arcontactus.toggleMenu');
    };
    ArContactUs.prototype.openCallbackPopup = function(){
        var $el = this.$element;
        $el.addClass('opened');
        this.closeMenu();
        this.stopAnimation(false);
        $el.find('.icons, .static').addClass('hide');
        $el.find('.pulsation').addClass('stop');
        $el.find('.callback-countdown-block-phone, .callback-countdown-block-sorry, .callback-countdown-block-timer').removeClass('display-flex');
        $el.find('.callback-countdown-block').addClass('display-flex');
        $el.find('.callback-countdown-block-phone').addClass('display-flex');
        $el.find('.callback-state').addClass('display-flex');
        this._callbackOpened = true;
        this.$element.trigger('arcontactus.openCallbackPopup');
    };
    ArContactUs.prototype.closeCallbackPopup = function(){
        var $el = this.$element;
        $el.removeClass('opened');
        $el.find('.messangers-block').removeClass(this.settings.menuInAnimationClass);
        $el.find('.arcontactus-close').removeClass('show-messageners-block');
        $el.find('.icons, .static').removeClass('hide');
        $el.find('.pulsation').removeClass('stop');
        $el.find('.callback-countdown-block').removeClass('display-flex');
        $el.find('.callback-state').removeClass('display-flex');
        this.startAnimation();
        this._callbackOpened = false;
        this.$element.trigger('arcontactus.closeCallbackPopup');
    };
    ArContactUs.prototype.startAnimation = function(){
        if (this._menuOpened || this._formOpened) {
            return false;
        }
        var $el = this.$element;
        var $this = this;
        var $container = $el.find('.icons-line');
        var $static = $el.find('.static');
        var width = $el.find('.icons-line>span:first-child').width();
        var offset = width + 40;
        if (this.settings.buttonSize === 'huge'){
            var xOffset = 2;
            var yOffset = 0;
        }
        if (this.settings.buttonSize === 'large'){
            var xOffset = 2;
            var yOffset = 0;
        }
        if (this.settings.buttonSize === 'medium'){
            var xOffset = 4;
            var yOffset = -2;
        }
        if (this.settings.buttonSize === 'small'){
            var xOffset = 4;
            var yOffset = -2;
        }
        var iconsCount = $el.find('.icons-line>span').length;
        var step = 0;
        if (this.settings.iconsAnimationSpeed === 0){
            return false;
        }
        this._animation = true;
        this._interval = setInterval(function(){
            if (step === 0){
                $container.parent().removeClass('hide');
                $static.addClass('hide');
            }
            var x = offset * step;
            var translate = 'translate(' + (-(x+xOffset)) + 'px, ' + yOffset + 'px)';
            $container.css({
                "-webkit-transform":translate,
                "-ms-transform":translate,
                "transform":translate
            });
            step++;
            if (step > iconsCount){
                if (step > iconsCount + 1){
                    if ($this.settings.iconsAnimationPause){
                        $this.stopAnimation(true);
                        if ($this._animation) {
                            $this._timeout = setTimeout(function(){
                                if ($this._callbackOpened || $this._menuOpened || $this._popupOpened || $this._formOpened){
                                    return false;
                                }
                                $this.startAnimation();
                            }, $this.settings.iconsAnimationPause);
                        }
                    }
                    step = 0;
                }
                $container.parent().addClass('hide');
                $static.removeClass('hide');
                var translate = 'translate(' + (-xOffset) + 'px, ' + yOffset + 'px)';
                $container.css({
                    "-webkit-transform":translate,
                    "-ms-transform":translate,
                    "transform":translate
                });
            }
        }, this.settings.iconsAnimationSpeed);
    };
    ArContactUs.prototype.stopAnimation = function(pause){
        clearInterval(this._interval);
        if (!pause) {
            this._animation = false;
            clearTimeout(this._timeout);
        }
        var $el = this.$element;
        var $container = $el.find('.icons-line');
        var $static = $el.find('.static');
        $container.parent().addClass('hide');
        $static.removeClass('hide');
        var translate = 'translate(' + (-2) + 'px, 0px)';
        $container.css({
            "-webkit-transform":translate,
            "-ms-transform":translate,
            "transform":translate
        });
    };
    ArContactUs.prototype.showPrompt = function(data){
        var $promptContainer = this.$element.find('.arcontactus-prompt');
        if (data && data.content){
            $promptContainer.find('.arcontactus-prompt-inner').html(data.content);
        }
        $promptContainer.addClass('active');
        this.$element.trigger('arcontactus.showPrompt');
    };
    ArContactUs.prototype.hidePrompt = function(){
        var $promptContainer = this.$element.find('.arcontactus-prompt');
        $promptContainer.removeClass('active');
        this.$element.trigger('arcontactus.hidePrompt');
    };
    ArContactUs.prototype.showForm = function(id){
        this._formOpened = true;
        this.stopAnimation(false);
        this.$element.addClass('open');
        this.$element.find('.arcu-forms-container').addClass('active');
        this.$element.find('.arcu-form-container.active').removeClass('active');
        this.$element.find('#arcu-form-' + id).addClass('active');
        if (this.$element.find('#form-icon-' + id).length) {
            this.$element.find('#form-icon-' + id).addClass('active');
            this.$element.find('.arcontactus-message-button .static').addClass('hide');
        }
        this.$element.trigger('arcontactus.showFrom', {id: id});
    };
    ArContactUs.prototype.hideForm = function(){
        this.$element.find('.arcu-forms-container').removeClass('active');
        this.$element.find('.form-icon').removeClass('active');
        this.$element.find('.arcontactus-message-button .static').removeClass('hide');
        this._formOpened = false;
        var $el = this.$element;
        var $this = this;
        setTimeout(function(){
            if (!$this._menuOpened){
                $el.removeClass('open');
            }
            $el.find('.arcu-form-success.active').removeClass('active');
            $el.find('.arcu-form-error.active').removeClass('active');
        }, 150);
        this.startAnimation();
        this.$element.trigger('arcontactus.hideFrom');
    };
    ArContactUs.prototype._insertPromptTyping = function(){
        var $promptContainer = this.$element.find('.arcontactus-prompt-inner');
        var $typing = $('<div>', {
            class: 'arcontactus-prompt-typing'
        });
        var $item = $('<div>');
        $typing.append($item);
        $typing.append($item.clone());
        $typing.append($item.clone());
        $promptContainer.append($typing);
    };
    ArContactUs.prototype.showPromptTyping = function(){
        var $promptContainer = this.$element.find('.arcontactus-prompt');
        $promptContainer.find('.arcontactus-prompt-inner').html('');
        this._insertPromptTyping();
        this.showPrompt({});
        this.$element.trigger('arcontactus.showPromptTyping');
    };
    ArContactUs.prototype.hidePromptTyping = function(){
        var $promptContainer = this.$element.find('.arcontactus-prompt');
        $promptContainer.removeClass('active');
        this.$element.trigger('arcontactus.hidePromptTyping');
    };
    ArContactUs.prototype.showWellcomeTyping = function(){
        var $wellcomeContainer = this.$element.find('.arcu-wellcome');
        var $icon = this.$element.find('.arcu-menu-header > .arcu-header-icon');
        if ($wellcomeContainer.find('.arcu-wellcome-msg.typing').length == 0) {
            var $wellcomeLine = $('<div>', {
                class: 'arcu-wellcome-msg typing'
            });
            var $wellcomeIcon = $('<div>', {
                class: 'arcu-wellcome-icon'
            });
            $wellcomeIcon.html($icon.clone());
            
            var $wellcomeTime = $('<div>', {
                class: 'arcu-wellcome-time'
            });
            var msgDate = new Date();
            
            $wellcomeTime.html(('0' + (msgDate.getHours())).slice(-2) + ':' + ('0' + (msgDate.getMinutes())).slice(-2));
            
            var $wellcomeContent = $('<div>', {
                class: 'arcu-wellcome-content'
            });
            
            var $typing = $('<div>', {
                class: 'arcontactus-prompt-typing'
            });
            var $item = $('<div>');
            $typing.append($item);
            $typing.append($item.clone());
            $typing.append($item.clone());
            
            $wellcomeContent.append($typing);
            
            $wellcomeLine.append($wellcomeTime);
            $wellcomeLine.append($wellcomeIcon);
            $wellcomeLine.append($wellcomeContent);
            $wellcomeContainer.append($wellcomeLine);
        }
    };
    ArContactUs.prototype.showWellcomeMessage = function(data){
        var $wellcomeContainer = this.$element.find('.arcu-wellcome');
        if ($wellcomeContainer.find('.arcu-wellcome-msg.typing').length) {
            $wellcomeContainer.find('.arcu-wellcome-msg.typing .arcu-wellcome-content').html(data.content);
            $wellcomeContainer.find('.arcu-wellcome-msg.typing').removeClass('typing');
        }
    };
    ArContactUs.prototype.getSettings = function(){
        console.log(this.settings);
    };
    ArContactUs.prototype.getVersion = function(){
        console.log(this.settings.pluginVersion);
    };
    ArContactUs.prototype.hideSubmenu = function(data){
        this.$element.find('.arcu-submenu-header').removeClass('active');
        $(data.id).parent().removeClass('active');
        $(data.id).parent().find('.arcu-submenu-container').removeClass('active');
        $(data.id).parent().find('.arcu-submenu-header').addClass('active');
        this.$element.find('.arcu-submenu-header').removeClass('active');
        this.$element.find('.arcu-submenu').removeClass('active');
        if (this.$element.find('.arcu-submenu-container.active').length == 0) {
            this.$element.find('.messangers-list').removeClass('arcu-submenu-active');
        } else {
            this.$element.find('.arcu-submenu-container.active').children('.arcu-submenu-header').addClass('active');
            this.$element.find('.arcu-submenu-container.active').children('.arcu-submenu').addClass('active');
        }
    };
    ArContactUs.prototype.showSubmenu = function(data){
        this.$element.find('.arcu-submenu-container').removeClass('active');
        this.$element.find('.arcu-submenu-container .arcu-submenu').removeClass('active');
        this.$element.find('.messangers-list li').removeClass('active');
        this.$element.find('.messangers-list').addClass('arcu-submenu-active');
        this.$element.find('.arcu-submenu-header').removeClass('active');
        $(data.id).parent().children('.arcu-submenu-container').addClass('active').addClass('animated');
        $(data.id).parent().children('.arcu-submenu-container').children('.arcu-submenu').addClass('active');
        setTimeout(function(){
            //$(data.id).parent().children('.arcu-submenu-container').removeClass('animated');
        }, 300);
        $(data.id).parents('.arcu-submenu-container').addClass('active');
        $(data.id).parents('li').addClass('active');
        $(data.id).parent().addClass('active');
        $(data.id).parent().children('.arcu-submenu-container').children('.arcu-submenu-header').addClass('active');
    };
    ArContactUs.prototype._backgroundStyle = function(){
        return 'background-color: ' + this.settings.theme;
    };
    ArContactUs.prototype._colorStyle = function(){
        return 'color: ' + this.settings.theme;
    };
    
    $.fn.contactUs = function(option){
        var args = Array.prototype.slice.call(arguments, 1);
        return this.each(function() {
            var $this = $(this),
                data = $this.data('ar.contactus');

            if (!data) {
                data = new ArContactUs(this, typeof option == 'object' && option);
                $this.data('ar.contactus', data);
            }

            if (typeof option == 'string' && option.charAt(0) !== '_') {
                data[option].apply(data, args);
            }
        });
    };
    $.fn.contactUs.Constructor = ArContactUs;
}(jQuery));