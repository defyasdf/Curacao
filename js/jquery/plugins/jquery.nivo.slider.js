(function ($) {
    var NivoSlider = function (element, options) {
        var settings = jQuery.extend({}, jQuery.fn.nivoSlider.defaults, options);
        var vars = {
            currentSlide: 0,
            currentImage: '',
            totalSlides: 0,
            randAnim: '',
            running: false,
            paused: false,
            stop: false
        };
        var slider = jQuery(element);
        slider.data('nivo:vars', vars);
        slider.css('position', 'relative');
        slider.addClass('nivoSlider');
        
        var kids = slider.children();
        
        kids.each(function () {
            var child = jQuery(this);
            var link = '';
            if (!child.is('img')) {
                if (child.is('a')) {
                    child.addClass('nivo-imageLink');
                    link = child;
                }
                child = child.find('img:first');
            }
            var childWidth = child.width();
            if (childWidth == 0) childWidth = child.attr('width');
            var childHeight = child.height();
            if (childHeight == 0) childHeight = child.attr('height');
            if (childWidth > slider.width()) {
                slider.width(childWidth);
            }
            if (childHeight > slider.height()) {
                slider.height(childHeight);
            }
            if (link != '') {
                link.css('display', 'none');
            }
            child.css('display', 'none');
            vars.totalSlides++;
        });
        
        if (settings.startSlide > 0) {
            if (settings.startSlide >= vars.totalSlides) settings.startSlide = vars.totalSlides - 1;
            vars.currentSlide = settings.startSlide;
        }

        if (jQuery(kids[vars.currentSlide]).is('img')) {
            vars.currentImage = jQuery(kids[vars.currentSlide]);
        } else {
            vars.currentImage = jQuery(kids[vars.currentSlide]).find('img:first');
        }

        if (jQuery(kids[vars.currentSlide]).is('a')) {
            jQuery(kids[vars.currentSlide]).css('display', 'block');
        }

        slider.css('background', 'url("' + vars.currentImage.attr('src') + '") no-repeat');
        slider.append(jQuery('<div class="nivo-caption"><p></p></div>').css({
            display: 'none',
            opacity: settings.captionOpacity
        }));
        
        var processCaption = function (settings) {
            
            var nivoCaption = jQuery('.nivo-caption', slider);
            
            if (vars.currentImage.attr('title') != '' && vars.currentImage.attr('title') != undefined) {
                var title = vars.currentImage.attr('title');
                if (title.substr(0, 1) == '#') title = jQuery(title).html();
                if (nivoCaption.css('display') == 'block') {
                    nivoCaption.find('p').fadeOut(settings.animSpeed, function () {
                        jQuery(this).html(title);
                        jQuery(this).fadeIn(settings.animSpeed);
                    });
                } else {
                    nivoCaption.find('p').html(title);
                }
                nivoCaption.fadeIn(settings.animSpeed);
            } else {
                nivoCaption.fadeOut(settings.animSpeed);
            }
        }
        
        processCaption(settings);
        
        var timer = 0;
        
        if (!settings.manualAdvance && kids.length > 1) {
            timer = setInterval(function () {
                nivoRun(slider, kids, settings, false);
            }, settings.pauseTime);
        }
        
        if (settings.directionNav) {
            slider.append('<div class="nivo-directionNav"><a class="nivo-prevNav">' + settings.prevText + '</a><a class="nivo-nextNav">' + settings.nextText + '</a></div>');
            if (settings.directionNavHide) {
                jQuery('.nivo-directionNav', slider).hide();
                slider.hover(function () {
                    jQuery('.nivo-directionNav', slider).show();
                }, function () {
                    jQuery('.nivo-directionNav', slider).hide();
                });
            }
            jQuery('a.nivo-prevNav', slider).live('click', function () {
                if (vars.running) return false;
                clearInterval(timer);
                timer = '';
                vars.currentSlide -= 2;
                nivoRun(slider, kids, settings, 'prev');
            });
            jQuery('a.nivo-nextNav', slider).live('click', function () {
                if (vars.running) return false;
                clearInterval(timer);
                timer = '';
                nivoRun(slider, kids, settings, 'next');
            });
        }
        
        if (settings.controlNav) {
            var nivoControl = jQuery('<div class="nivo-controlNav"></div>');
            slider.append(nivoControl);
            for (var i = 0; i < kids.length; i++) {
                if (settings.controlNavThumbs) {
                    var child = kids.eq(i);
                    if (!child.is('img')) {
                        child = child.find('img:first');
                    }
                    if (settings.controlNavThumbsFromRel) {
                        nivoControl.append('<a class="nivo-control" rel="' + i + '"><img src="' + child.attr('rel') + '" alt="" /></a>');
                    } else {
                        nivoControl.append('<a class="nivo-control" rel="' + i + '"><img src="' + child.attr('src').replace(settings.controlNavThumbsSearch, settings.controlNavThumbsReplace) + '" alt="" /></a>');
                    }
                } else {
                    nivoControl.append('<a class="nivo-control" rel="' + i + '">' + (i + 1) + '</a>');
                }
            }
            jQuery('.nivo-controlNav a:eq(' + vars.currentSlide + ')', slider).addClass('active');
            jQuery('.nivo-controlNav a', slider).live('click', function () {
                if (vars.running) return false;
                if (jQuery(this).hasClass('active')) return false;
                clearInterval(timer);
                timer = '';
                slider.css('background', 'url("' + vars.currentImage.attr('src') + '") no-repeat');
                vars.currentSlide = jQuery(this).attr('rel') - 1;
                nivoRun(slider, kids, settings, 'control');
            });
        }
        
        if (settings.keyboardNav) {
            jQuery(window).keypress(function (event) {
                if (event.keyCode == '37') {
                    if (vars.running) return false;
                    clearInterval(timer);
                    timer = '';
                    vars.currentSlide -= 2;
                    nivoRun(slider, kids, settings, 'prev');
                }
                if (event.keyCode == '39') {
                    if (vars.running) return false;
                    clearInterval(timer);
                    timer = '';
                    nivoRun(slider, kids, settings, 'next');
                }
            });
        }
        
        if (settings.pauseOnHover) {
            slider.hover(function () {
                vars.paused = true;
                clearInterval(timer);
                timer = '';
            }, function () {
                vars.paused = false;
                if (timer == '' && !settings.manualAdvance) {
                    timer = setInterval(function () {
                        nivoRun(slider, kids, settings, false);
                    }, settings.pauseTime);
                }
            });
        }
        
        slider.bind('nivo:animFinished', function () {
            vars.running = false;
            jQuery(kids).each(function () {
                if (jQuery(this).is('a')) {
                    jQuery(this).css('display', 'none');
                }
            });
            if (jQuery(kids[vars.currentSlide]).is('a')) {
                jQuery(kids[vars.currentSlide]).css('display', 'block');
            }
            if (timer == '' && !vars.paused && !settings.manualAdvance) {
                timer = setInterval(function () {
                    nivoRun(slider, kids, settings, false);
                }, settings.pauseTime);
            }
            settings.afterChange.call(this);
        });
        var createSlices = function (slider, settings, vars) {
            for (var i = 0; i < settings.slices; i++) {
                var sliceWidth = Math.round(slider.width() / settings.slices);
                if (i == settings.slices - 1) {
                    slider.append(jQuery('<div class="nivo-slice"></div>').css({
                        left: (sliceWidth * i) + 'px',
                        width: (slider.width() - (sliceWidth * i)) + 'px',
                        height: '0px',
                        opacity: '0',
                        background: 'url("' + vars.currentImage.attr('src') + '") no-repeat -' + ((sliceWidth + (i * sliceWidth)) - sliceWidth) + 'px 0%',
                        href: jQuery(vars.currentImage).parent().attr('href')
                    }));
            		//add onclick function
			        jQuery('.nivo-slice').click(function(){
			        	document.location = jQuery(vars.currentImage).parent().attr('href');
			        })
                    
                } else {
                    slider.append(jQuery('<div class="nivo-slice"></div>').css({
                        left: (sliceWidth * i) + 'px',
                        width: sliceWidth + 'px',
                        height: '0px',
                        opacity: '0',
                        background: 'url("' + vars.currentImage.attr('src') + '") no-repeat -' + ((sliceWidth + (i * sliceWidth)) - sliceWidth) + 'px 0%',
                        href: jQuery(vars.currentImage).parent().attr('href')
                    }));
			        jQuery('.nivo-slice').click(function(){
			        	document.location = jQuery(vars.currentImage).parent().attr('href');
			        })
                    
                }
            }
        }
        
        var createBoxes = function (slider, settings, vars) {
            var boxWidth = Math.round(slider.width() / settings.boxCols);
            var boxHeight = Math.round(slider.height() / settings.boxRows);
            for (var rows = 0; rows < settings.boxRows; rows++) {
                for (var cols = 0; cols < settings.boxCols; cols++) {
                    if (cols == settings.boxCols - 1) {
                        slider.append(jQuery('<div class="nivo-box"></div>').css({
                            opacity: 0,
                            left: (boxWidth * cols) + 'px',
                            top: (boxHeight * rows) + 'px',
                            width: (slider.width() - (boxWidth * cols)) + 'px',
                            height: boxHeight + 'px',
                            background: 'url("' + vars.currentImage.attr('src') + '") no-repeat -' + ((boxWidth + (cols * boxWidth)) - boxWidth) + 'px -' + ((boxHeight + (rows * boxHeight)) - boxHeight) + 'px'
                        }));
                    } else {
                        slider.append(jQuery('<div class="nivo-box"></div>').css({
                            opacity: 0,
                            left: (boxWidth * cols) + 'px',
                            top: (boxHeight * rows) + 'px',
                            width: boxWidth + 'px',
                            height: boxHeight + 'px',
                            background: 'url("' + vars.currentImage.attr('src') + '") no-repeat -' + ((boxWidth + (cols * boxWidth)) - boxWidth) + 'px -' + ((boxHeight + (rows * boxHeight)) - boxHeight) + 'px'
                        }));
                    }
                }
            }
        }
        
        var nivoRun = function (slider, kids, settings, nudge) {
        
            settings.effect = 'slideInLeft';
            settings.animSpeed = 250;
            settings.pauseTime = 4500;
            settings.slices = 1;
            settings.boxCols = settings.boxRows = 1;
                    
            var vars = slider.data('nivo:vars');
            if (vars && (vars.currentSlide == vars.totalSlides - 1)) {
                settings.lastSlide.call(this);
            }
            if ((!vars || vars.stop) && !nudge) return false;
            settings.beforeChange.call(this);
            if (!nudge) {
                slider.css('background', 'url("' + vars.currentImage.attr('src') + '") no-repeat');
            } else {
                if (nudge == 'prev') {
                    slider.css('background', 'url("' + vars.currentImage.attr('src') + '") no-repeat');
                }
                if (nudge == 'next') {
                    slider.css('background', 'url("' + vars.currentImage.attr('src') + '") no-repeat');
                }
            }
            vars.currentSlide++;
            if (vars.currentSlide == vars.totalSlides) {
                vars.currentSlide = 0;
                settings.slideshowEnd.call(this);
            }
            if (vars.currentSlide < 0) vars.currentSlide = (vars.totalSlides - 1);
            if (jQuery(kids[vars.currentSlide]).is('img')) {
                vars.currentImage = jQuery(kids[vars.currentSlide]);
            } else {
                vars.currentImage = jQuery(kids[vars.currentSlide]).find('img:first');
            }
            if (settings.controlNav) {
                jQuery('.nivo-controlNav a', slider).removeClass('active');
                jQuery('.nivo-controlNav a:eq(' + vars.currentSlide + ')', slider).addClass('active');
            }
            processCaption(settings);
            jQuery('.nivo-slice', slider).remove();
            jQuery('.nivo-box', slider).remove();
            
            if (settings.effect == 'random') {
                var anims = new Array('sliceDownRight', 'sliceDownLeft', 'sliceUpRight', 'sliceUpLeft', 'sliceUpDown', 'sliceUpDownLeft', 'fold', 'fade', 'boxRandom', 'boxRain', 'boxRainReverse', 'boxRainGrow', 'boxRainGrowReverse');
                vars.randAnim = anims[Math.floor(Math.random() * (anims.length + 1))];
                if (vars.randAnim == undefined) vars.randAnim = 'fade';
            }
            
            if (settings.effect.indexOf(',') != -1) {
                var anims = settings.effect.split(',');
                vars.randAnim = anims[Math.floor(Math.random() * (anims.length))];
                if (vars.randAnim == undefined) vars.randAnim = 'fade';
            }
            
            vars.running = true;
            
            //
            
            if (settings.effect == 'sliceDown' || settings.effect == 'sliceDownRight' || vars.randAnim == 'sliceDownRight' || settings.effect == 'sliceDownLeft' || vars.randAnim == 'sliceDownLeft') {
                createSlices(slider, settings, vars);
                var timeBuff = 0;
                var i = 0;
                var slices = jQuery('.nivo-slice', slider);
                
                if (settings.effect == 'sliceDownLeft' || vars.randAnim == 'sliceDownLeft') slices = jQuery('.nivo-slice', slider)._reverse();
                
                slices.each(function () {
                    var slice = jQuery(this);
                    slice.css({ 'top': '0px' });
                
                    if (i == settings.slices - 1) {
                        setTimeout(function () {
                            slice.animate({
                                height: '100%',
                                opacity: '1.0'
                            }, settings.animSpeed, '', function () {
                                slider.trigger('nivo:animFinished');
                            });
                        }, (100 + timeBuff));
                    } else {
                        setTimeout(function () {
                            slice.animate({
                                height: '100%',
                                opacity: '1.0'
                            }, settings.animSpeed);
                        }, (100 + timeBuff));
                    }
                    timeBuff += 50;
                    i++;
                });
                
            } else if (settings.effect == 'sliceUp' || settings.effect == 'sliceUpRight' || vars.randAnim == 'sliceUpRight' || settings.effect == 'sliceUpLeft' || vars.randAnim == 'sliceUpLeft') {
                createSlices(slider, settings, vars);
                var timeBuff = 0;
                var i = 0;
                var slices = jQuery('.nivo-slice', slider);
                if (settings.effect == 'sliceUpLeft' || vars.randAnim == 'sliceUpLeft') slices = jQuery('.nivo-slice', slider)._reverse();
                slices.each(function () {
                    var slice = jQuery(this);
                    slice.css({
                        'bottom': '0px'
                    });
                    if (i == settings.slices - 1) {
                        setTimeout(function () {
                            slice.animate({
                                height: '100%',
                                opacity: '1.0'
                            }, settings.animSpeed, '', function () {
                                slider.trigger('nivo:animFinished');
                            });
                        }, (100 + timeBuff));
                    } else {
                        setTimeout(function () {
                            slice.animate({
                                height: '100%',
                                opacity: '1.0'
                            }, settings.animSpeed);
                        }, (100 + timeBuff));
                    }
                    timeBuff += 50;
                    i++;
                });
            } else if (settings.effect == 'sliceUpDown' || settings.effect == 'sliceUpDownRight' || vars.randAnim == 'sliceUpDown' || settings.effect == 'sliceUpDownLeft' || vars.randAnim == 'sliceUpDownLeft') {
                createSlices(slider, settings, vars);
                var timeBuff = 0;
                var i = 0;
                var v = 0;
                var slices = jQuery('.nivo-slice', slider);
                if (settings.effect == 'sliceUpDownLeft' || vars.randAnim == 'sliceUpDownLeft') slices = jQuery('.nivo-slice', slider)._reverse();
                slices.each(function () {
                    var slice = jQuery(this);
                    if (i == 0) {
                        slice.css('top', '0px');
                        i++;
                    } else {
                        slice.css('bottom', '0px');
                        i = 0;
                    }
                    if (v == settings.slices - 1) {
                        setTimeout(function () {
                            slice.animate({
                                height: '100%',
                                opacity: '1.0'
                            }, settings.animSpeed, '', function () {
                                slider.trigger('nivo:animFinished');
                            });
                        }, (100 + timeBuff));
                    } else {
                        setTimeout(function () {
                            slice.animate({
                                height: '100%',
                                opacity: '1.0'
                            }, settings.animSpeed);
                        }, (100 + timeBuff));
                    }
                    timeBuff += 50;
                    v++;
                });
            } else if (settings.effect == 'fold' || vars.randAnim == 'fold') {
                createSlices(slider, settings, vars);
                var timeBuff = 0;
                var i = 0;
                jQuery('.nivo-slice', slider).each(function () {
                    var slice = jQuery(this);
                    var origWidth = slice.width();
                    slice.css({
                        top: '0px',
                        height: '100%',
                        width: '0px'
                    });
                    if (i == settings.slices - 1) {
                        setTimeout(function () {
                            slice.animate({
                                width: origWidth,
                                opacity: '1.0'
                            }, settings.animSpeed, '', function () {
                                slider.trigger('nivo:animFinished');
                            });
                        }, (100 + timeBuff));
                    } else {
                        setTimeout(function () {
                            slice.animate({
                                width: origWidth,
                                opacity: '1.0'
                            }, settings.animSpeed);
                        }, (100 + timeBuff));
                    }
                    timeBuff += 50;
                    i++;
                });
            } else if (settings.effect == 'fade' || vars.randAnim == 'fade') {
                createSlices(slider, settings, vars);
                var firstSlice = jQuery('.nivo-slice:first', slider);
                firstSlice.css({
                    'height': '100%',
                    'width': slider.width() + 'px'
                });
                firstSlice.animate({
                    opacity: '1.0'
                }, (settings.animSpeed * 2), '', function () {
                    slider.trigger('nivo:animFinished');
                });
            } else if (settings.effect == 'slideInRight' || vars.randAnim == 'slideInRight') {
                createSlices(slider, settings, vars);
                var firstSlice = jQuery('.nivo-slice:first', slider);
                firstSlice.css({
                    'height': '100%',
                    'width': '0px',
                    'opacity': '1'
                });
                firstSlice.animate({
                    width: slider.width() + 'px'
                }, (settings.animSpeed * 2), '', function () {
                    slider.trigger('nivo:animFinished');
                });
            } else if (settings.effect == 'slideInLeft' || vars.randAnim == 'slideInLeft') {
                createSlices(slider, settings, vars);
                var firstSlice = jQuery('.nivo-slice:first', slider);
                firstSlice.css({
                    'height': '100%',
                    'width': '0px',
                    'opacity': '1',
                    'left': '',
                    'right': '0px'
                });
                firstSlice.animate({
                    width: slider.width() + 'px'
                }, (settings.animSpeed * 2), '', function () {
                    firstSlice.css({
                        'left': '0px',
                        'right': ''
                    });
                    slider.trigger('nivo:animFinished');
                });
            } else if (settings.effect == 'boxRandom' || vars.randAnim == 'boxRandom') {
                createBoxes(slider, settings, vars);
                var totalBoxes = settings.boxCols * settings.boxRows;
                var i = 0;
                var timeBuff = 0;
                var boxes = shuffle(jQuery('.nivo-box', slider));
                boxes.each(function () {
                    var box = jQuery(this);
                    if (i == totalBoxes - 1) {
                        setTimeout(function () {
                            box.animate({
                                opacity: '1'
                            }, settings.animSpeed, '', function () {
                                slider.trigger('nivo:animFinished');
                            });
                        }, (100 + timeBuff));
                    } else {
                        setTimeout(function () {
                            box.animate({
                                opacity: '1'
                            }, settings.animSpeed);
                        }, (100 + timeBuff));
                    }
                    timeBuff += 20;
                    i++;
                });
            } else if (settings.effect == 'boxRain' || vars.randAnim == 'boxRain' || settings.effect == 'boxRainReverse' || vars.randAnim == 'boxRainReverse' || settings.effect == 'boxRainGrow' || vars.randAnim == 'boxRainGrow' || settings.effect == 'boxRainGrowReverse' || vars.randAnim == 'boxRainGrowReverse') {
                createBoxes(slider, settings, vars);
                var totalBoxes = settings.boxCols * settings.boxRows;
                var i = 0;
                var timeBuff = 0;
                var rowIndex = 0;
                var colIndex = 0;
                var box2Darr = new Array();
                box2Darr[rowIndex] = new Array();
                var boxes = jQuery('.nivo-box', slider);
                if (settings.effect == 'boxRainReverse' || vars.randAnim == 'boxRainReverse' || settings.effect == 'boxRainGrowReverse' || vars.randAnim == 'boxRainGrowReverse') {
                    boxes = jQuery('.nivo-box', slider)._reverse();
                }
                boxes.each(function () {
                    box2Darr[rowIndex][colIndex] = jQuery(this);
                    colIndex++;
                    if (colIndex == settings.boxCols) {
                        rowIndex++;
                        colIndex = 0;
                        box2Darr[rowIndex] = new Array();
                    }
                });
                for (var cols = 0; cols < (settings.boxCols * 2); cols++) {
                    var prevCol = cols;
                    for (var rows = 0; rows < settings.boxRows; rows++) {
                        if (prevCol >= 0 && prevCol < settings.boxCols) {
                            (function (row, col, time, i, totalBoxes) {
                                var box = jQuery(box2Darr[row][col]);
                                var w = box.width();
                                var h = box.height();
                                if (settings.effect == 'boxRainGrow' || vars.randAnim == 'boxRainGrow' || settings.effect == 'boxRainGrowReverse' || vars.randAnim == 'boxRainGrowReverse') {
                                    box.width(0).height(0);
                                }
                                if (i == totalBoxes - 1) {
                                    setTimeout(function () {
                                        box.animate({
                                            opacity: '1',
                                            width: w,
                                            height: h
                                        }, settings.animSpeed / 1.3, '', function () {
                                            slider.trigger('nivo:animFinished');
                                        });
                                    }, (100 + time));
                                } else {
                                    setTimeout(function () {
                                        box.animate({
                                            opacity: '1',
                                            width: w,
                                            height: h
                                        }, settings.animSpeed / 1.3);
                                    }, (100 + time));
                                }
                            })(rows, prevCol, timeBuff, i, totalBoxes);
                            i++;
                        }
                        prevCol--;
                    }
                    timeBuff += 100;
                }
            }
            
        }
        
        var shuffle = function (arr) {
            for (var j, x, i = arr.length; i; j = parseInt(Math.random() * i), x = arr[--i], arr[i] = arr[j], arr[j] = x);
            return arr;
        }
        
        var trace = function (msg) {
            if (this.console && typeof console.log != "undefined") console.log(msg);
        }
        
        this.stop = function () {
            if (!jQuery(element).data('nivo:vars').stop) {
                jQuery(element).data('nivo:vars').stop = true;
                trace('Stop Slider');
            }
        }
        
        this.start = function () {
            if (jQuery(element).data('nivo:vars').stop) {
                jQuery(element).data('nivo:vars').stop = false;
                trace('Start Slider');
            }
        }
        
        settings.afterLoad.call(this);
        return this;
    };
    
    jQuery.fn.nivoSlider = function (options) {
        return this.each(function (key, value) {
            var element = jQuery(this);
            if (element.data('nivoslider')) return element.data('nivoslider');
            var nivoslider = new NivoSlider(this, options);
            element.data('nivoslider', nivoslider);
        });
    };
    
    jQuery.fn.nivoSlider.defaults = {
        effect: 'random',
        slices: 1,
        boxCols: 1,
        boxRows: 1,
        animSpeed: 250,
        pauseTime: 4500,
        startSlide: 0,
        directionNav: false,
        directionNavHide: true,
        controlNav: true,
        controlNavThumbs: false,
        controlNavThumbsFromRel: false,
        controlNavThumbsSearch: '.jpg',
        controlNavThumbsReplace: '_thumb.jpg',
        keyboardNav: true,
        pauseOnHover: true,
        manualAdvance: false,
        captionOpacity: 0.5,
        prevText: 'Prev',
        nextText: 'Next',
        beforeChange: function () {},
        afterChange: function () {},
        slideshowEnd: function () {},
        lastSlide: function () {},
        afterLoad: function () {}
    };
    jQuery.fn._reverse = []._reverse || [].reverse;
})(jQuery);