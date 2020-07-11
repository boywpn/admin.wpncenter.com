/*!
 * jQuery Simple Color Picker v1.0
 * https://github.com/tomreid76/jquery-simplecolorpicker
 *
 * Copyright 2013 Tom Reid
 * Released under the MIT license
 */
;(function ($, window) {

    // our plugin constructor
    var SimpleColorPicker = function (el, opts) {
        this.$el = $(el);
        this.options = opts;
        this.metadata = eval(this.$el.data('options'));
    };

    // the plugin prototype
    SimpleColorPicker.prototype = {

        //default options
        defaults: {
            defaultColor: {name: 'default (white)', hex: 'fff'}, //color used if input does not have a hex in the value attr.
            borderWidth: 1, //border width for input and picker box
            cellWidth: 20, //width of the color cells
            cellHeight: 20, //height of the color cells
            cellBorder: 1, //border width of the color cells
            elWidth: 25, //overall width of the picker box
            elHeight: 25, //height of the input
            cols: 5,// # of cols
            colors: [
                {name: 'brown',  hex: '7a4100'},
                {name: 'green',  hex: '00aa00'},
                {name: 'red',    hex: 'cc0000'},
                {name: 'aqua',   hex: '039799'},
                {name: 'pink',   hex: 'b0307a'},
                {name: 'purple', hex: '762ca7'},
                {name: 'orange', hex: 'cc6600'},
                {name: 'gray',   hex: '555555'},
                {name: 'black',  hex: '000000'},
                {name: 'blue',   hex: '0000dd'}
            ],
            iconPos: 'right',
            aniSpeed: 50, //slide animation speed, set to 0 for no anim
            lsClass: 'color-picker-chooser', //css class for the picker
            textCurrentColor: 'Current color: ' //text shown in title attribute of input once a selection is made
        },

        init: function () {

            //reference to this
            var _t = this;

            // get all config and merge (options are passed in calling plugin function, metadata can be attached to DOM element)
            _t.config = $.extend(true, {}, _t.defaults, _t.options, _t.metadata);

            //calculate dimensions of picker dropdown
            _t.config.totalWidth = _t.config.cols * (_t.config.cellWidth + (2 * _t.config.cellBorder));
            _t.config.totalHeight = Math.ceil((_t.config.colors.length) / _t.config.cols) * (_t.config.cellHeight + (2 * _t.config.cellBorder));

            //set CSS, bg-color, etc...
            _t.setupInputElement();

            //get the picker
            _t.$cp = _t.getPickerMenu();

            //append the picker to body
            $('body').append(_t.$cp);

            //bind events
            _t.bindPickerEvents();

            //bind resize re-position picker
            $(window).resize(function () {
                _t.setPickerPosition(_t.$cp);
            });
            return _t;
        },

        geticonPos: function() {
            var pos;
            switch (this.config.iconPos) {
                case 'center':
                    pos = 'center';
                    break;
                case 'left':
                    pos = '4px center';
                    break;
                case 'right':
                default:
                    pos = (this.config.elWidth - 20) + 'px';
                    break;
            }
            return pos;
        },

        setupInputElement: function() {
            var _t = this,
                defaultColor = (_t.$el.val() && _t.$el.val() !== '') ? _t.$el.val() : _t.config.defaultColor.hex;
            _t.$el.css({
                'backgroundColor': '#' + defaultColor,
                'backgroundPosition': _t.geticonPos(),
                'borderWidth': _t.config.borderWidth + 'px',
                'width': _t.config.elWidth + 'px',
                'height': _t.config.elHeight + 'px',
                'color': '#' + _t.$el.val()
            });
        },

        //close picker
        close: function() {
            var _t = this;
            _t.$cp.slideUp(_t.config.aniSpeed).find('li').removeClass('selected');
            _t.$el.removeClass('open').focus();
            $(document).off('mouseup.cp' + _t.colorPickerInstance, 'html');
        },

        //open picker
        open: function() {
            var _t = this;
            _t.setPickerPosition(_t.$cp); //reset picker position
            _t.$el.addClass('open');
            _t.$cp.slideDown(_t.config.aniSpeed);
            $(document).on('mouseup.cp' + _t.colorPickerInstance, 'html',  function() {_t.close.call(_t)});
        },

        //set position of picker to attach to bot-left of input
        setPickerPosition: function($cp) {
            var _t = this,
                offset = _t.$el.offset(),
                height = _t.$el.height();
            $cp.css({
                'top': offset.top + height + _t.config.borderWidth,
                'left': offset.left
            });
        },

        //creates the cell array and returns to caller
        getCells: function() {
            var _t = this,

                cellCount = _t.config.colors.length,
                $cells = $('<div/>'),
                i,
                $cell;

            for (i = 0; i < cellCount; i++) {
                $cell = $("<li tabindex='0' title='"+ _t.config.colors[i].name +"' data-color='" + _t.config.colors[i].hex + "'></li>");
                $cell.css({
                    'width': _t.config.cellWidth + 'px',
                    'height': _t.config.cellHeight + 'px',
                    'borderWidth': _t.config.cellBorder + 'px',
                    'backgroundColor': '#' + _t.config.colors[i].hex
                });

                $cells.append($cell);
            }
            return $cells;
        },

        //create picker container
        getPickerMenu: function() {
            var _t = this;

            _t.colorPickerInstance = $('.' + _t.config.lsClass).length + 1;
            var $cp = $("<ul class='" + _t.config.lsClass + "' id='cp-" + _t.colorPickerInstance + "' />"),
                $cells;

            _t.setPickerPosition($cp);
            $cp.css({
                'borderWidth': _t.config.borderWidth + 'px',
                'width': _t.config.totalWidth + 'px',
                'height': _t.config.totalHeight + 'px',
                'display': 'none'
            });
            //get cells
            $cells = _t.getCells();
            //append cells
            $cp.append($cells.children());
            //return picker
            return $cp;
        },

        //bind events on input
        bindPickerEvents: function() {
            var _t = this,
                $cell = _t.$cp.find('li');

            _t.$el.bind({
                mouseup: function (e) {
                    (_t.$cp.is(':visible')) ? _t.close() : _t.open();
                    e.stopPropagation();
                },
                keydown: function (e) {
                    switch (e.which) {
                        //enter key
                        case 13:
                            _t.$el.trigger('mouseup');
                            break;
                        //escape
                        case 27:
                            _t.close();
                            e.stopPropagation();
                            break;
                        //down arrow
                        case 40:
                            _t.open();
                            _t.$cp.find('li:first').focus().addClass('selected');
                            break;
                        case 9:
                            if (_t.$cp.is(':visible')) {
                                _t.$cp.find('li:first').focus().addClass('selected');
                                e.preventDefault();
                            }
                            break;
                    }
                }
            });
            $cell.bind({
                keydown: function (e) {
                    var $t = $(this),
                        $all = $t.siblings().andSelf();

                    switch (e.which) {
                        //enter key
                        case 13:
                            $t.trigger('mouseup');
                            break;
                        //escape key
                        case 27:
                            $t.removeClass('selected');

                            _t.close();
                            e.stopPropagation();
                            break;
                        //left arrow
                        case 37:
                            if ($t.prevAll().length === 0) {
                                $t.removeClass('selected').siblings(':last').focus().addClass('selected');
                            } else {
                                $t.removeClass('selected').prev().focus().addClass('selected');
                            }
                            break;
                        //up arrow
                        case 38:
                            var pos = $all.index($t) - _t.config.cols;
                            if (pos > -1) {
                                $t.removeClass('selected');
                                $($all[pos]).addClass('selected').focus();
                            } else {
                                $t.removeClass('selected');
                                _t.close();
                            }
                            break;
                        //right arrow and tab
                        case 39: case 9:
                        if ($t.nextAll().length === 0) {
                            $t.removeClass('selected').siblings(':first').focus().addClass('selected');
                        } else {
                            $t.removeClass('selected').next().focus().addClass('selected');
                        }
                        break;
                        //down arrow
                        case 40:
                            var newPos = $all.index($t) + _t.config.cols;
                            if (newPos < $all.length) {
                                $t.removeClass('selected');
                                $($all[newPos]).addClass('selected').focus();
                            }
                            break;

                    }
                    return false;
                },
                mouseup: function (e) {
                    var $this = $(this);
                    $this.siblings().andSelf().removeClass('selected');
                    //chosen color
                    var newColor = $this.attr('data-color'),
                        newColorName = $this.attr('title');
                    //update input
                    _t.$el.val(newColor).removeClass('open').focus().css({
                        'backgroundColor': '#' + newColor,
                        'color': '#' + newColor
                    }).attr('title', _t.config.textCurrentColor + newColorName);
                    _t.close();
                    e.stopPropagation();
                    return false;
                }
            });
        }
    };

    $.fn.simpleColorPicker = function(opts) {
        return this.each(function() {
            new SimpleColorPicker(this, opts).init();
        });
    };

})(jQuery, window);