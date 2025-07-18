(function($) {
    var api = wp.customize;

    api.bind('pane-contents-reflowed', function() {

        // Reflow sections
        var sections = [];

        api.section.each(function(section) {

            if ( 'taaza_extended_section' !== section.params.type || 'undefined' === typeof section.params.section ) {

                return;
            }

            sections.push(section);
        });

        sections.sort(api.utils.prioritySort).reverse();

        $.each(sections, function(i, section) {

            var parentContainer = $('#sub-accordion-section-' + section.params.section);

            parentContainer.children('.section-meta').after(section.headContainer);
        });

        // Reflow panels
        var panels = [];

        api.panel.each(function(panel) {

            if ( 'taaza_extend_panel' !== panel.params.type || 'undefined' === typeof panel.params.panel ) {

                return;
            }

            panels.push(panel);
        });

        panels.sort(api.utils.prioritySort).reverse();

        $.each(panels, function(i, panel) {

            var parentContainer = $('#sub-accordion-panel-' + panel.params.panel);

            parentContainer.children('.panel-meta').after(panel.headContainer);
        });
    });

    // Extend Panel
    var _panelEmbed = wp.customize.Panel.prototype.embed;
    var _panelIsContextuallyActive = wp.customize.Panel.prototype.isContextuallyActive;
    var _panelAttachEvents = wp.customize.Panel.prototype.attachEvents;

    wp.customize.Panel = wp.customize.Panel.extend({

        attachEvents: function() {

            if ( 'taaza_extend_panel' !== this.params.type || 'undefined' === typeof this.params.panel ) {

                _panelAttachEvents.call(this);

                return;
            }

            _panelAttachEvents.call(this);

            var panel = this;

            panel.expanded.bind(function(expanded) {

                var parent = api.panel(panel.params.panel);

                if (expanded) {

                    parent.contentContainer.addClass('current-panel-parent');
                } else {

                    parent.contentContainer.removeClass('current-panel-parent');
                }
            });

            panel.container.find('.customize-panel-back')
                .off('click keydown')
                .on('click keydown', function(event) {

                    if (api.utils.isKeydownButNotEnterEvent(event)) {

                        return;
                    }

                    event.preventDefault(); // Keep this AFTER the key filter above

                    if (panel.expanded()) {

                        api.panel(panel.params.panel).expand();
                    }
                });
        },

        embed: function() {

            if ( 'taaza_extend_panel' !== this.params.type || 'undefined' === typeof this.params.panel ) {

                _panelEmbed.call(this);

                return;
            }

            _panelEmbed.call(this);

            var panel = this;
            var parentContainer = $('#sub-accordion-panel-' + this.params.panel);

            parentContainer.append(panel.headContainer);
        },

        isContextuallyActive: function() {

            if ( 'taaza_extend_panel' !== this.params.type ) {

                return _panelIsContextuallyActive.call(this);
            }

            var panel = this;
            var children = this._children('panel', 'section');

            api.panel.each(function(child) {

                if (!child.params.panel) {

                    return;
                }

                if (child.params.panel !== panel.id) {

                    return;
                }

                children.push(child);
            });

            children.sort(api.utils.prioritySort);

            var activeCount = 0;

            _(children).each(function(child) {

                if (child.active() && child.isContextuallyActive()) {

                    activeCount += 1;
                }
            });

            return (activeCount !== 0);
        }
    });

    // Extend Section
    var _sectionEmbed = wp.customize.Section.prototype.embed;
    var _sectionIsContextuallyActive = wp.customize.Section.prototype.isContextuallyActive;
    var _sectionAttachEvents = wp.customize.Section.prototype.attachEvents;

    wp.customize.Section = wp.customize.Section.extend({

        attachEvents: function() {

            if ( 'taaza_extended_section' !== this.params.type || 'undefined' === typeof this.params.section ) {

                _sectionAttachEvents.call(this);

                return;
            }

            _sectionAttachEvents.call(this);

            var section = this;

            section.expanded.bind(function(expanded) {

                var parent = api.section(section.params.section);

                if (expanded) {

                    parent.contentContainer.addClass('current-section-parent');
                } else {

                    parent.contentContainer.removeClass('current-section-parent');
                }
            });

            section.container.find('.customize-section-back')
                .off('click keydown')
                .on('click keydown', function(event) {

                    if (api.utils.isKeydownButNotEnterEvent(event)) {

                        return;
                    }

                    event.preventDefault(); // Keep this AFTER the key filter above

                    if (section.expanded()) {

                        api.section(section.params.section).expand();
                    }
                });
        },

        embed: function() {

            if ( 'taaza_extended_section' !== this.params.type || 'undefined' === typeof this.params.section ) {

                _sectionEmbed.call(this);

                return;
            }

            _sectionEmbed.call(this);

            var section = this;
            var parentContainer = $('#sub-accordion-section-' + this.params.section);

            parentContainer.append(section.headContainer);
        },

        isContextuallyActive: function() {

            if ( 'taaza_extended_section' !== this.params.type ) {

                return _sectionIsContextuallyActive.call(this);
            }

            var section = this;
            var children = this._children('section', 'control');

            api.section.each(function(child) {

                if (!child.params.section) {

                    return;
                }

                if (child.params.section !== section.id) {

                    return;
                }

                children.push(child);
            });

            children.sort(api.utils.prioritySort);

            var activeCount = 0;

            _(children).each(function(child) {

                if ('undefined' !== typeof child.isContextuallyActive) {

                    if (child.active() && child.isContextuallyActive()) {

                        activeCount += 1;
                    }
                } else {

                    if (child.active()) {

                        activeCount += 1;
                    }
                }
            });

            return (activeCount !== 0);
        }
    });
})(jQuery);

jQuery( document ).ready( function($){

    // Responsive switchers
    $( '.customize-control .wdt-responsive-switchers button' ).on( 'click', function( event ) {

        // Set up variables
        var $this       = $( this ),
            $devices    = $( '.wdt-responsive-switchers' ),
            $device     = $( event.currentTarget ).data( 'device' ),
            $control    = $( '.customize-control.has-responsive-switchers' ),
            $body       = $( '.wp-full-overlay' ),
            $footer_devices = $( '.wp-full-overlay-footer .devices' );


        // Button class
        $devices.find( 'button' ).removeClass( 'active' );
        $devices.find( 'button.preview-' + $device ).addClass( 'active' );

        // Control class
        $control.find( '.control-wrap' ).removeClass( 'active' );
        $control.find( '.control-wrap.' + $device ).addClass( 'active' );
        $control.removeClass( 'control-device-desktop control-device-tablet control-device-tablet-landscape control-device-mobile' ).addClass( 'control-device-' + $device );

        // Wrapper class
        $body.removeClass( 'preview-desktop preview-tablet preview-tablet-landscape preview-mobile' ).addClass( 'preview-' + $device );

        // Panel footer buttons
        $footer_devices.find( 'button' ).removeClass( 'active' ).attr( 'aria-pressed', false );
        $footer_devices.find( 'button.preview-' + $device ).addClass( 'active' ).attr( 'aria-pressed', true );

        // Open switchers
        if ( $this.hasClass( 'preview-desktop' ) ) {
            $control.toggleClass( 'responsive-switchers-open' );
        }
    });

    // If panel footer buttons clicked
    $( '.wp-full-overlay-footer .devices button' ).on( 'click', function( event ) {

        // Set up variables
        var $this       = $( this ),
            $devices    = $( '.customize-control.has-responsive-switchers .wdt-responsive-switchers' ),
            $device     = $( event.currentTarget ).data( 'device' ),
            $control    = $( '.customize-control.has-responsive-switchers' );

        // Button class
        $devices.find( 'button' ).removeClass( 'active' );
        $devices.find( 'button.preview-' + $device ).addClass( 'active' );

        // Control class
        $control.find( '.control-wrap' ).removeClass( 'active' );
        $control.find( '.control-wrap.' + $device ).addClass( 'active' );
        $control.removeClass( 'control-device-desktop control-device-tablet control-device-tablet-landscape control-device-mobile' ).addClass( 'control-device-' + $device );

        // Open switchers
        if ( ! $this.hasClass( 'preview-desktop' ) ) {
            $control.addClass( 'responsive-switchers-open' );
        } else {
            $control.removeClass( 'responsive-switchers-open' );
        }
    });
});