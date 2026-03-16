/**
 * Repeater Field JavaScript
 * Keeps repeater rows and hidden JSON input in sync.
 */

( function( $ ) {
	'use strict';

	$( document ).ready( function() {
		init_repeater_fields( document );
		bind_repeater_events();
		observe_repeater_fields();
	} );

	function bind_repeater_events() {
		// Avoid duplicate handlers if this script is loaded multiple times.
		$( document ).off( '.dafRepeater' );

		$( document ).on( 'click.dafRepeater', '.directorist-repeater .action-plus', function( e ) {
			e.preventDefault();
			add_repeater_item( $( this ).closest( '.directorist-repeater' ) );
		} );

		$( document ).on( 'click.dafRepeater', '.directorist-repeater .action-minus', function( e ) {
			e.preventDefault();
			remove_repeater_item( $( this ).closest( '.directorist-repeater' ), $( this ) );
		} );

		$( document ).on( 'change.dafRepeater input.dafRepeater', '.directorist-repeater input, .directorist-repeater select, .directorist-repeater textarea', function() {
			update_repeater_hidden_input( $( this ).closest( '.directorist-repeater' ) );
		} );
	}

	function observe_repeater_fields() {
		if ( ! window.MutationObserver || ! document.body ) {
			return;
		}

		var observer = new MutationObserver( function( mutations ) {
			mutations.forEach( function( mutation ) {
				if ( ! mutation.addedNodes || ! mutation.addedNodes.length ) {
					return;
				}

				mutation.addedNodes.forEach( function( node ) {
					if ( ! node || 1 !== node.nodeType ) {
						return;
					}

					var $node = $( node );
					if ( $node.is( '.directorist-repeater' ) || $node.find( '.directorist-repeater' ).length ) {
						init_repeater_fields( node );
					}
				} );
			} );
		} );

		observer.observe(
			document.body,
			{
				childList: true,
				subtree: true
			}
		);
	}

	function init_repeater_fields( scope ) {
		var $scope = $( scope );
		var $repeaters = $scope.is( '.directorist-repeater' ) ? $scope : $scope.find( '.directorist-repeater' );

		$repeaters.each( function() {
			var $repeater = $( this );
			var $container = $repeater.find( '.directorist-repeater-field-body' );
			var $items = $container.find( '.repeater-fieldset' );

			if ( ! $container.length || ! $items.length ) {
				return;
			}

			if ( ! $repeater.attr( 'data-repeater-label' ) ) {
				var label_text = $repeater.find( '.fieldset-title' ).first().data( 'label' ) || '';

				if ( ! label_text ) {
					var title_text = $.trim( $repeater.find( '.fieldset-title' ).first().text() );
					label_text = title_text ? title_text.replace( /\s*#\d+\s*$/, '' ) : '';
				}

				$repeater.attr( 'data-repeater-label', label_text );
			}

			$items.each( function() {
				init_repeater_item( $( this ) );
			} );

			reindex_repeater_items( $repeater );
			update_repeater_hidden_input( $repeater );
		} );
	}

	function add_repeater_item( $repeater ) {
		var $container = $repeater.find( '.directorist-repeater-field-body' );
		var $template = $container.find( '.repeater-fieldset' ).first();

		if ( ! $container.length || ! $template.length ) {
			return;
		}

		var $new_item = $template.clone();

		$new_item.find( 'input, textarea' ).each( function() {
			var $field = $( this );

			if ( $field.hasClass( 'directorist-repeater-hidden-input' ) ) {
				return;
			}

			if ( $field.is( ':checkbox' ) || $field.is( ':radio' ) ) {
				$field.prop( 'checked', false );
			} else {
				$field.val( '' );
			}
		} );

		$new_item.find( 'select' ).each( function() {
			$( this ).val( '' );
		} );

		$container.append( $new_item );
		init_repeater_item( $new_item );
		reindex_repeater_items( $repeater );
		update_repeater_hidden_input( $repeater );
	}

	function remove_repeater_item( $repeater, $button ) {
		var $container = $repeater.find( '.directorist-repeater-field-body' );
		var $item = $button.closest( '.repeater-fieldset' );

		if ( ! $item.length || ! $container.length ) {
			return;
		}

		// Keep at least one item in the UI.
		if ( $container.find( '.repeater-fieldset' ).length <= 1 ) {
			$item.find( 'input, textarea' ).not( '.directorist-repeater-hidden-input' ).val( '' );
			$item.find( 'input:checkbox, input:radio' ).prop( 'checked', false );
			$item.find( 'select' ).val( '' );
			update_repeater_hidden_input( $repeater );
			return;
		}

		$item.remove();
		reindex_repeater_items( $repeater );
		update_repeater_hidden_input( $repeater );
	}

	function reindex_repeater_items( $repeater ) {
		var $container = $repeater.find( '.directorist-repeater-field-body' );
		var label_text = $repeater.attr( 'data-repeater-label' ) || '';

		$container.find( '.repeater-fieldset' ).each( function( index ) {
			var $item = $( this );
			$item.attr( 'data-id', index + 1 );

			if ( label_text ) {
				$item.find( '.fieldset-title' ).text( label_text + ' #' + ( index + 1 ) );
			}

			$item.find( 'input, select, textarea' ).each( function() {
				var $field = $( this );

				if ( $field.hasClass( 'directorist-repeater-hidden-input' ) ) {
					return;
				}

				var name = $field.attr( 'name' );
				if ( ! name ) {
					return;
				}

				var parsed_name = parse_repeater_name( name );
				if ( parsed_name ) {
					$field.attr( 'name', parsed_name.base + '[' + index + '][' + parsed_name.key + ']' + parsed_name.array_suffix );
				}

				// Keep field IDs unique when IDs are index-based.
				var id = $field.attr( 'id' );
				if ( id ) {
					var matched_id = id.match( /^(.*)_\d+(_.*)?$/ );
					if ( matched_id ) {
						$field.attr( 'id', matched_id[1] + '_' + index + ( matched_id[2] || '' ) );
					}
				}
			} );
		} );
	}

	function parse_repeater_name( name ) {
		if ( ! name || -1 === name.indexOf( '[' ) ) {
			return null;
		}

		// Supports:
		// parent[0][field]
		// custom_field[parent][0][field]
		var matches = name.match( /^(.*)\[\d+\]\[([^\]]+)\](\[\])?$/ );

		if ( ! matches ) {
			return null;
		}

		return {
			base: matches[1],
			key: matches[2],
			array_suffix: matches[3] || ''
		};
	}

	function init_repeater_item( $item ) {
		// Rebuild select options for cloned rows from data-options.
		$item.find( 'select' ).each( function() {
			var $select = $( this );
			var options = $select.data( 'options' );

			if ( ! options || ! Array.isArray( options ) ) {
				return;
			}

			var selected_value = $select.val();
			var placeholder = $select.attr( 'placeholder' ) || '';

			$select.empty();
			$select.append( '<option value="">' + placeholder + '</option>' );

			options.forEach( function( option ) {
				if ( ! option || 'undefined' === typeof option.option_value ) {
					return;
				}

				var option_value = option.option_value;
				var option_label = option.option_label ? option.option_label : option_value;
				$select.append( '<option value="' + option_value + '">' + option_label + '</option>' );
			} );

			if ( selected_value ) {
				$select.val( selected_value );
			}
		} );
	}

	function update_repeater_hidden_input( $repeater ) {
		if ( ! $repeater || ! $repeater.length ) {
			return;
		}

		var $hidden_input = $repeater.find( '.directorist-repeater-hidden-input' );
		if ( ! $hidden_input.length ) {
			return;
		}

		var field_data = array_from_repeater( $repeater );
		$hidden_input.val( JSON.stringify( field_data ) );
	}

	function array_from_repeater( $repeater ) {
		var field_data = [];
		var $container = $repeater.find( '.directorist-repeater-field-body' );

		$container.find( '.repeater-fieldset' ).each( function() {
			var $fieldset = $( this );
			var item_data = {};
			var processed_by_name = {};

			$fieldset.find( 'input, select, textarea' ).each( function() {
				var $field = $( this );

				if ( $field.hasClass( 'directorist-repeater-hidden-input' ) ) {
					return;
				}

				var name = $field.attr( 'name' );
				if ( ! name || processed_by_name[name] ) {
					return;
				}

				var parsed_name = parse_repeater_name( name );
				if ( ! parsed_name ) {
					return;
				}

				processed_by_name[name] = true;
				var field_key = parsed_name.key;

				if ( $field.is( ':checkbox' ) ) {
					if ( parsed_name.array_suffix ) {
						var checked_values = [];

						$fieldset.find( 'input[type="checkbox"][name="' + name + '"]:checked' ).each( function() {
							checked_values.push( $( this ).val() );
						} );

						item_data[field_key] = checked_values;
					} else {
						item_data[field_key] = $field.is( ':checked' ) ? $field.val() : '';
					}
					return;
				}

				if ( $field.is( ':radio' ) ) {
					var checked_radio = $fieldset.find( 'input[type="radio"][name="' + name + '"]:checked' ).val();
					item_data[field_key] = checked_radio ? checked_radio : '';
					return;
				}

				if ( $field.is( 'select[multiple]' ) ) {
					item_data[field_key] = $field.val() || [];
					return;
				}

				item_data[field_key] = $field.val() || '';
			} );

			if ( Object.keys( item_data ).length ) {
				field_data.push( item_data );
			}
		} );

		return field_data;
	}

	window.DirectoristRepeater = window.DirectoristRepeater || {};
	window.DirectoristRepeater.init = function( scope ) {
		init_repeater_fields( scope || document );
	};
} )( jQuery );
