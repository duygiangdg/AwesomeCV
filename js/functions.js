var baselThemeModule;

(function ($) {
	"use strict";

	baselThemeModule = (function () {


		var baselTheme = {
			popupEffect: 'mfp-move-horizontal',
			shopLoadMoreBtn: '.basel-products-load-more.load-on-scroll',
			supports_html5_storage: false,
			ajaxLinks: '.basel-product-categories a, .widget_product_categories a, .widget_layered_nav_filters a, .filters-area a, body.post-type-archive-product:not(.woocommerce-account) .woocommerce-pagination a, body.tax-product_cat:not(.woocommerce-account) .woocommerce-pagination a, .basel-woocommerce-layered-nav a, .widget_product_tag_cloud a, .basel-products-shop-view a, .basel-price-filter a, .woocommerce-widget-layered-nav a, .basel-clear-filters-wrapp a, .basel-woocommerce-sort-by a',
			mainCarouselArg: {
				rtl: $('body').hasClass('rtl'),
				items: 1,
				autoplay: (basel_settings.product_slider_autoplay),
				autoplayTimeout: 3000,
				loop: (basel_settings.product_slider_autoplay),
				dots: false,
				nav: false,
				autoHeight: (basel_settings.product_slider_auto_height == 'yes'),
				navText: false,
				onRefreshed: function () {
					$(window).resize();
				}
			}
		};

		/* Storage Handling */
		try {
			baselTheme.supports_html5_storage = ('sessionStorage' in window && window.sessionStorage !== null);

			window.sessionStorage.setItem('basel', 'test');
			window.sessionStorage.removeItem('basel');
		} catch (err) {
			baselTheme.supports_html5_storage = false;
		}

		return {

			init: function () {

				this.headerBanner();

				this.fixedHeaders();

				this.verticalHeader();

				this.splitNavHeader();

				this.visibleElements();

				this.bannersHover();

				this.parallax();

				this.googleMap();

				this.scrollTop();

				this.quickViewInit();

				this.quickShop();

				this.sidebarMenu();

				this.addToCart();

				this.productImages();

				this.productImagesGallery();

				this.stickyDetails();

				this.mfpPopup();

				this.swatchesVariations();

				this.swatchesOnGrid();

				this.blogMasonry();

				this.blogLoadMore();

				this.productsLoadMore();

				this.productsTabs();

				this.portfolioLoadMore();

				this.equalizeColumns();

				this.menuSetUp();

				this.menuOffsets();

				this.onePageMenu();

				this.mobileNavigation();

				this.simpleDropdown();

				this.woocommerceWrappTable();

				this.wishList();

				this.compare();

				this.baselCompare();

				this.promoPopup();

				this.cookiesPopup();

				this.productVideo();

				this.product360Button();

				this.btnsToolTips();

				this.stickyFooter();

				this.updateWishListNumberInit();

				this.cartWidget();

				this.ajaxFilters();

				this.shopPageInit();

				this.filtersArea();

				this.categoriesMenu();

				this.searchFullScreen();

				this.loginTabs();

				this.productAccordion();

				this.productCompact();

				this.countDownTimer();

				this.mobileFastclick();

				this.nanoScroller();

				this.woocommerceComments();

				this.woocommerceQuantity();

				this.initZoom();

				this.videoPoster();

				this.addToCartAllTypes();

				this.contentPopup();

				this.mobileSearchIcon();

				this.shopHiddenSidebar();

				this.loginSidebar();

				this.shopLoader();

				this.stickyAddToCart();

				this.stickySidebarBtn();

				this.productLoaderPosition();

				this.lazyLoading();

				this.owlCarouselInit();

				this.baselSliderLazyLoad();

				this.portfolioPhotoSwipe();

				this.sortByWidget();

				this.instagramAjaxQuery();

				this.footerWidgetsAccordion();

				$(window).resize();

				$('body').addClass('document-ready');

				$(document.body).on('updated_cart_totals', function () {
					baselThemeModule.woocommerceWrappTable();
				});

			},

			/**
			 *--------------------------------------------------------------------------
			 * Footer widgets accordion
			 *--------------------------------------------------------------------------
			*/
			footerWidgetsAccordion: function () {
				if ($(window).width() > 767) {
					return;
				}

				$('.footer-widget-collapse .widget-title').on('click', function () {
					var $title = $(this);
					var $widget = $title.parent();
					var $content = $widget.find('> *:not(.widget-title)');

					if ($widget.hasClass('footer-widget-opened')) {
						$widget.removeClass('footer-widget-opened');
						$content.stop().slideUp(200);
					} else {
						$widget.addClass('footer-widget-opened');
						$content.stop().slideDown(200);
					}
				});

			},

			/**
			 *--------------------------------------------------------------------------
			 * Instagram AJAX
			 *--------------------------------------------------------------------------
			 */
			instagramAjaxQuery: function () {
				$('.instagram-widget').each(function () {
					var $instagram = $(this);
					if (!$instagram.hasClass('instagram-with-error')) {
						return;
					}

					var username = $instagram.data('username');
					var atts = $instagram.data('atts');
					var request_param = username.indexOf('#') > -1 ? 'explore/tags/' + username.substr(1) : username;

					var url = 'https://www.instagram.com/' + request_param + '/';

					$instagram.addClass('loading');

					$.ajax({
						url: url,
						success: function (response) {
							$.ajax({
								url: basel_settings.ajaxurl,
								data: {
									action: 'basel_instagram_ajax_query',
									body: response,
									atts: atts,
								},
								dataType: 'json',
								method: 'POST',
								success: function (response) {
									$instagram.parent().html(response);
									baselThemeModule.owlCarouselInit();
								},
								error: function (data) {
									console.log('instagram ajax error');
								},
							});
						},
						error: function (data) {
							console.log('instagram ajax error');
						},
					});

				});
			},

			/**
			 *--------------------------------------------------------------------------
			 * "Sort by" widget reinit
			 *--------------------------------------------------------------------------
			 */
			sortByWidget: function () {
				if ($('body').hasClass('basel-ajax-shop-off')) return;

				$('.woocommerce-ordering').on('change', 'select.orderby', function () {
					var $form = $(this).closest('form');

					$form.find('[name="_pjax"]').remove();

					$.pjax({
						container: '.main-page-wrapper',
						timeout: basel_settings.pjax_timeout,
						url: '?' + $form.serialize(),
						scrollTo: false
					});
				});

				$('.woocommerce-ordering').submit(function (e) {
					e.preventDefault(e);
				});
			},

            /**
            *--------------------------------------------------------------------------
            * Portfolio photo swipe
            *--------------------------------------------------------------------------
            */

			portfolioPhotoSwipe: function () {
				$(document).on('click', '.portfolio-enlarge', function (e) {
					e.preventDefault();
					var $parent = $(this).parents('.portfolio-entry');
					var index = $parent.index();
					var items = getPortfolioImages();
					baselThemeModule.callPhotoSwipe(index, items);
				});

				var getPortfolioImages = function () {
					var items = [];
					$('.portfolio-entry').find('figure a img').each(function () {
						items.push({
							src: $(this).attr('src'),
							w: $(this).attr('width'),
							h: $(this).attr('height')
						});
					});
					return items;
				};
			},

            /**
            *--------------------------------------------------------------------------
            * Product filters
            *--------------------------------------------------------------------------
            */

			productFilters: function () {
				//Select checkboxes value
				var removeValue = function ($mainInput, currentVal) {
					if ($mainInput.length == 0) return;
					var mainInputVal = $mainInput.val();
					if (mainInputVal.indexOf(',') > 0) {
						$mainInput.val(mainInputVal.replace(',' + currentVal, '').replace(currentVal + ',', ''));
					} else {
						$mainInput.val(mainInputVal.replace(currentVal, ''));
					}
				}

				$('.basel-pf-checkboxes li > .pf-value').on('click', function (e) {
					e.preventDefault();
					var $this = $(this);
					var $li = $this.parent();
					var $widget = $this.parents('.basel-pf-checkboxes');
					var $mainInput = $widget.find('.result-input');
					var $results = $widget.find('.basel-pf-results');

					var multiSelect = $widget.hasClass('multi_select');
					var mainInputVal = $mainInput.val();
					var currentText = $this.data('title');
					var currentVal = $this.data('val');

					if (multiSelect) {
						if (!$li.hasClass('pf-active')) {
							if (mainInputVal == '') {
								$mainInput.val(currentVal);
							} else {
								$mainInput.val(mainInputVal + ',' + currentVal);
							}
							$results.prepend('<li class="selected-value" data-title="' + currentVal + '">' + currentText + '</li>');
							$li.addClass('pf-active');
						} else {
							removeValue($mainInput, currentVal);
							$results.find('li[data-title="' + currentVal + '"]').remove();
							$li.removeClass('pf-active');
						}
					} else {
						if (!$li.hasClass('pf-active')) {
							$mainInput.val(currentVal);
							$results.find('.selected-value').remove();
							$results.prepend('<li class="selected-value" data-title="' + currentVal + '">' + currentText + '</li>');
							$li.parents('.basel-scroll-content').find('.pf-active').removeClass('pf-active');
							$li.addClass('pf-active');
						} else {
							$mainInput.val('');
							$results.find('.selected-value').remove();
							$li.removeClass('pf-active');
						}
					}
				});

				//Label clear
				$('.basel-pf-checkboxes').on('click', '.selected-value', function () {
					var $this = $(this);
					var $widget = $this.parents('.basel-pf-checkboxes');
					var $mainInput = $widget.find('.result-input');
					var currentVal = $this.data('title');

					//Price filter clear
					if (currentVal == 'price-filter') {
						var min = $this.data('min');
						var max = $this.data('max');
						var $slider = $widget.find('.price_slider_widget');
						$slider.slider('values', 0, min);
						$slider.slider('values', 1, max);
						$widget.find('.min_price').val('');
						$widget.find('.max_price').val('');
						$(document.body).trigger('filter_price_slider_slide', [min, max, min, max, $slider]);
						return;
					}

					removeValue($mainInput, currentVal);
					$widget.find('.pf-value[data-val="' + currentVal + '"]').parent().removeClass('pf-active');
					$this.remove();
				});

				//Checkboxes value dropdown
				$('.basel-pf-checkboxes').each(function () {
					var $this = $(this);
					var $btn = $this.find('.basel-pf-title');
					var $list = $btn.siblings('.basel-pf-dropdown');
					var multiSelect = $this.hasClass('multi_select');

					$btn.on('click', function (e) {
						var target = e.target;
						if ($(target).is($btn.find('.selected-value'))) return;

						if (!$this.hasClass('opened')) {
							$this.addClass('opened');
							$list.slideDown(100);
							setTimeout(function () {
								baselThemeModule.nanoScroller();
							}, 300);
						} else {
							close();
						}
					});

					$(document).on('click', function (e) {
						var target = e.target;
						if ($this.hasClass('opened') && (multiSelect && !$(target).is($this) && !$(target).parents().is($this)) || (!multiSelect && !$(target).is($btn) && !$(target).parents().is($btn))) {
							close();
						}
					});

					var close = function () {
						$this.removeClass('opened');
						$list.slideUp(100);
					}
				});

				var removeEmptyValues = function ($selector) {
					$selector.find('.basel-pf-checkboxes').each(function () {
						if (!$(this).find('input[type="hidden"]').val()) {
							$(this).find('input[type="hidden"]').remove();
						}
					});
				}

				var changeFormAction = function ($form) {
					var activeCat = $form.find('.basel-pf-categories .pf-active .pf-value');
					if (activeCat.length > 0) {
						$form.attr('action', activeCat.attr('href'));
					}
				}

				//Price slider init
				$(document.body).on('filter_price_slider_create filter_price_slider_slide', function (event, min, max, minPrice, maxPrice, $slider) {
					var minHtml = accounting.formatMoney(min, {
						symbol: woocommerce_price_slider_params.currency_format_symbol,
						decimal: woocommerce_price_slider_params.currency_format_decimal_sep,
						thousand: woocommerce_price_slider_params.currency_format_thousand_sep,
						precision: woocommerce_price_slider_params.currency_format_num_decimals,
						format: woocommerce_price_slider_params.currency_format
					});

					var maxHtml = accounting.formatMoney(max, {
						symbol: woocommerce_price_slider_params.currency_format_symbol,
						decimal: woocommerce_price_slider_params.currency_format_decimal_sep,
						thousand: woocommerce_price_slider_params.currency_format_thousand_sep,
						precision: woocommerce_price_slider_params.currency_format_num_decimals,
						format: woocommerce_price_slider_params.currency_format
					});

					$slider.siblings('.filter_price_slider_amount').find('span.from').html(minHtml);
					$slider.siblings('.filter_price_slider_amount').find('span.to').html(maxHtml);

					var $results = $slider.parents('.basel-pf-checkboxes').find('.basel-pf-results');
					var value = $results.find('.selected-value');
					if (min == minPrice && max == maxPrice) {
						value.remove();
					} else {
						if (value.length == 0) {
							$results.prepend('<li class="selected-value" data-title="price-filter" data-min="' + minPrice + '" data-max="' + maxPrice + '">' + minHtml + ' - ' + maxHtml + '</li>');
						} else {
							value.html(minHtml + ' - ' + maxHtml);
						}
					}

					$(document.body).trigger('price_slider_updated', [min, max]);
				});

				$('.basel-pf-price-range .price_slider_widget').each(function () {
					var $this = $(this);
					var $minInput = $this.siblings('.filter_price_slider_amount').find('.min_price');
					var $maxInput = $this.siblings('.filter_price_slider_amount').find('.max_price');
					var minPrice = parseInt($minInput.data('min'));
					var maxPrice = parseInt($maxInput.data('max'));
					var currentMinPrice = parseInt($minInput.val());
					var currentMaxPrice = parseInt($maxInput.val());

					$('.price_slider_widget, .price_label').show();

					$this.slider({
						range: true,
						animate: true,
						min: minPrice,
						max: maxPrice,
						values: [currentMinPrice, currentMaxPrice],
						create: function () {
							if (currentMinPrice == minPrice && currentMaxPrice == maxPrice) {
								$minInput.val('');
								$maxInput.val('');
							}
							$(document.body).trigger('filter_price_slider_create', [currentMinPrice, currentMaxPrice, minPrice, maxPrice, $this]);
						},
						slide: function (event, ui) {
							if (ui.values[0] == minPrice && ui.values[1] == maxPrice) {
								$minInput.val('');
								$maxInput.val('');
							} else {
								$minInput.val(ui.values[0]);
								$maxInput.val(ui.values[1]);
							}
							$(document.body).trigger('filter_price_slider_slide', [ui.values[0], ui.values[1], minPrice, maxPrice, $this]);
						},
						change: function (event, ui) {
							$(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
						}
					});
				});

				//Submit filter form
				$('.basel-product-filters').one('click', '.basel-pf-btn button', function (e) {
					var $form = $(this).parents('.basel-product-filters');
					removeEmptyValues($form);
					changeFormAction($form);

					if (typeof ($.fn.pjax) == 'undefined' || !$form.hasClass('with-ajax')) return;
					$.pjax({
						container: '.main-page-wrapper',
						timeout: basel_settings.pjax_timeout,
						url: $form.attr('action'),
						data: $form.serialize(),
						scrollTo: false
					});
					$(this).prop('disabled', true);
				});

				//Create labels after ajax
				$('.basel-pf-checkboxes .pf-active > .pf-value').each(function () {
					var resultsWrapper = $(this).parents('.basel-pf-checkboxes').find('.basel-pf-results');
					resultsWrapper.prepend('<li class="selected-value" data-title="' + $(this).data('val') + '">' + $(this).data('title') + '</li>');
				});

			},

            /**
             *--------------------------------------------------------------------------
             * Owl carousel init function
             *--------------------------------------------------------------------------
             */

			owlCarouselInit: function () {
				$('[data-owl-carousel]').each(function () {
					var $this = $(this);
					var $owl = $this.find('.owl-carousel');
					var options = {
						rtl: $('body').hasClass('rtl'),
						items: $this.data('desktop') ? $this.data('desktop') : 1,
						responsive: {
							979: {
								items: $this.data('desktop') ? $this.data('desktop') : 1
							},
							768: {
								items: $this.data('desktop_small') ? $this.data('desktop_small') : 1
							},
							479: {
								items: $this.data('tablet') ? $this.data('tablet') : 1
							},
							0: {
								items: $this.data('mobile') ? $this.data('mobile') : 1
							}
						},
						autoplay: $this.data('autoplay') == 'yes' ? true : false,
						autoplayHoverPause: $this.data('autoplay') == 'yes' ? true : false,
						autoplayTimeout: $this.data('speed') ? $this.data('speed') : 5000,
						dots: $this.data('hide_pagination_control') == 'yes' ? false : true,
						nav: $this.data('hide_prev_next_buttons') == 'yes' ? false : true,
						autoHeight: $this.data('autoheight') == 'yes' ? true : false,
						slideBy: $this.data('scroll_per_page') == '' ? 1 : 'page',
						navText: false,
						center: $this.data('center_mode') == 'yes' ? true : false,
						loop: $this.data('wrap') == 'yes' ? true : false,
						dragEndSpeed: $this.data('dragendspeed') ? $this.data('dragendspeed') : 200,
						onRefreshed: function () {
							$(window).resize();
						}
					};

					if ($this.data('sliding_speed')) {
						options.smartSpeed = $this.data('sliding_speed');
						options.dragEndSpeed = $this.data('sliding_speed');
					}

					if ($this.data('animation')) {
						options.animateOut = $this.data('animation');
						options.mouseDrag = false;
					}

					if ($this.data('content_animation')) {
						function determinePseudoActive() {
							var id = $owl.find('.owl-item.active').find('.basel-slide').attr('id');
							$owl.find('.owl-item.pseudo-active').removeClass('pseudo-active');
							var $els = $owl.find('[id="' + id + '"]');
							$els.each(function () {
								if (!$(this).parent().hasClass('active')) {
									$(this).parent().addClass('pseudo-active');
								}
							});
						}
						determinePseudoActive();
						options.onTranslated = function () {
							determinePseudoActive();
						};
					}

					$(window).on('vc_js', function () {
						$owl.trigger('refresh.owl.carousel');
					});

					$owl.owlCarousel(options);

					if ($this.data('autoheight') == 'yes') {
						$owl.imagesLoaded(function () {
							$owl.trigger('refresh.owl.carousel');
						});
					}
				});
			},

            /**
             *--------------------------------------------------------------------------
             * Basel slider lazyload
             *--------------------------------------------------------------------------
             */

			baselSliderLazyLoad: function () {
				$('.basel-slider').on('changed.owl.carousel', function (event) {
					var $this = $(this);
					var active = $this.find('.owl-item').eq(event.item.index);
					var id = active.find('.basel-slide').attr('id');
					var $els = $this.find('[id="' + id + '"]');

					active.find('.basel-slide').addClass('basel-loaded');
					$els.each(function () {
						$(this).addClass('basel-loaded');
					});
				});
			},

            /**
             *--------------------------------------------------------------------------
             * Lazy loading
             *--------------------------------------------------------------------------
             */
			lazyLoading: function () {
				if (!window.addEventListener || !window.requestAnimationFrame || !document.getElementsByClassName) return;

				// start
				var pItem = document.getElementsByClassName('basel-lazy-load'), pCount, timer;

				$(document).on('basel-images-loaded added_to_cart', function () {
					inView();
				})

				$('.basel-scroll-content, .basel-sidebar-content').scroll(function () {
					$(document).trigger('basel-images-loaded');
				})
				// $(document).on( 'scroll', '.basel-scroll-content', function() {
				//     $(document).trigger('basel-images-loaded');
				// })

				// WooCommerce tabs fix
				$('.wc-tabs > li').on('click', function () {
					$(document).trigger('basel-images-loaded');
				});

				// scroll and resize events
				window.addEventListener('scroll', scroller, false);
				window.addEventListener('resize', scroller, false);

				// DOM mutation observer
				if (MutationObserver) {

					var observer = new MutationObserver(function () {
						// console.log('mutated', pItem.length, pCount)
						if (pItem.length !== pCount) inView();
					});

					observer.observe(document.body, { subtree: true, childList: true, attributes: true, characterData: true });

				}

				// initial check
				inView();

				// throttled scroll/resize
				function scroller() {

					timer = timer || setTimeout(function () {
						timer = null;
						inView();
					}, 100);

				}


				// image in view?
				function inView() {

					if (pItem.length) requestAnimationFrame(function () {
						var offset = parseInt(basel_settings.lazy_loading_offset);
						var wT = window.pageYOffset, wB = wT + window.innerHeight + offset, cRect, pT, pB, p = 0;

						while (p < pItem.length) {

							cRect = pItem[p].getBoundingClientRect();
							pT = wT + cRect.top;
							pB = pT + cRect.height;

							if (wT < pB && wB > pT && !pItem[p].loaded) {
								loadFullImage(pItem[p], p);
							}
							else p++;

						}

						pCount = pItem.length;

					});

				}


				// replace with full image
				function loadFullImage(item, i) {

					item.onload = addedImg;

					item.src = item.dataset.baselSrc;
					if (typeof (item.dataset.srcset) != 'undefined') {
						item.srcset = item.dataset.srcset;
					}

					item.loaded = true

					// replace image
					function addedImg() {

						requestAnimationFrame(function () {
							item.classList.add('basel-loaded')

							var $masonry = jQuery(item).parents('.view-masonry .gallery-images, .grid-masonry, .masonry-container');
							if ($masonry.length > 0) {
								$masonry.isotope('layout');
							}
							var $categories = jQuery(item).parents('.categories-masonry');
							if ($categories.length > 0) {
								$categories.packery();
							}
						});

					}

				}

			},

            /**
             *--------------------------------------------------------------------------
             * Product loder position
             *--------------------------------------------------------------------------
             */
			productLoaderPosition: function () {
				var reculc = function () {
					$('.basel-products-loader').each(function () {
						var $loader = $(this),
							$loaderWrap = $loader.parent();

						if ($loader.length == 0) return;

						$loader.css('left', $loaderWrap.offset().left + $loaderWrap.outerWidth() / 2);
					});
				};

				$(window).on('resize', reculc);

				reculc();
			},
            /**
             *--------------------------------------------------------------------------
             * Sticky sidebar button
             *--------------------------------------------------------------------------
             */

			stickySidebarBtn: function () {
				var $trigger = $('.basel-show-sidebar-btn');
				var $stickyBtn = $('.shop-sidebar-opener');

				if ($stickyBtn.length <= 0 || $trigger.length <= 0 || $(window).width() >= 1024) return;

				var stickySidebarBtnToggle = function () {
					var btnOffset = $trigger.offset().top + $trigger.outerHeight();
					var windowScroll = $(window).scrollTop();

					if (btnOffset < windowScroll) {
						$stickyBtn.addClass('basel-sidebar-btn-shown');
					} else {
						$stickyBtn.removeClass('basel-sidebar-btn-shown');
					}
				};

				stickySidebarBtnToggle();

				$(window).scroll(stickySidebarBtnToggle);
				$(window).resize(stickySidebarBtnToggle);
			},

            /**
             *--------------------------------------------------------------------------
             * Sticky add to cart
             *--------------------------------------------------------------------------
             */

			stickyAddToCart: function () {
				var $trigger = $('.summary-inner .cart');
				var $stickyBtn = $('.basel-sticky-btn');

				if ($stickyBtn.length <= 0 || $trigger.length <= 0 || ($(window).width() <= 768 && $stickyBtn.hasClass('mobile-off'))) return;

				var summaryOffset = $trigger.offset().top + $trigger.outerHeight();
				var $scrollToTop = $('.scrollToTop');

				var stickyAddToCartToggle = function () {
					var windowScroll = $(window).scrollTop();
					var windowHeight = $(window).height();
					var documentHeight = $(document).height();

					if (summaryOffset < windowScroll && windowScroll + windowHeight != documentHeight) {
						$stickyBtn.addClass('basel-sticky-btn-shown');
						$scrollToTop.addClass('basel-sticky-btn-shown');

					} else if (windowScroll + windowHeight == documentHeight || summaryOffset > windowScroll) {
						$stickyBtn.removeClass('basel-sticky-btn-shown');
						$scrollToTop.removeClass('basel-sticky-btn-shown');
					}
				};

				stickyAddToCartToggle();

				$(window).scroll(stickyAddToCartToggle);

				$('.basel-sticky-add-to-cart').on('click', function (e) {
					e.preventDefault();
					$('html, body').animate({
						scrollTop: $('.summary-inner').offset().top
					}, 800);
				});

				$('.basel-sticky-btn-wishlist').on('click', function (e) {
					if (!$(this).hasClass('exists')) e.preventDefault();
					$('.summary-inner .basel-scroll-content > .yith-wcwl-add-to-wishlist .add_to_wishlist').trigger('click');
				});

				$('body').on('added_to_wishlist', function () {
					$('.basel-sticky-btn-wishlist').addClass('exists');
				});
			},

            /**
             *--------------------------------------------------------------------------
             * Shop loader position
             *--------------------------------------------------------------------------
             */

			shopLoader: function () {

				var loaderClass = '.basel-shop-loader',
					contentClass = '.products[data-source="main_loop"]',
					sidebarClass = '.area-sidebar-shop',
					sidebarLeftClass = 'sidebar-left',
					hiddenClass = 'hidden-loader',
					hiddenTopClass = 'hidden-from-top',
					hiddenBottomClass = 'hidden-from-bottom';

				var loaderVerticalPosition = function () {
					var $products = $(contentClass),
						$loader = $products.parent().find(loaderClass);

					if ($products.length < 1) return;

					var offset = $(window).height() / 2,
						scrollTop = $(window).scrollTop(),
						holderTop = $products.offset().top - offset,
						holderHeight = $products.height(),
						holderBottom = holderTop + holderHeight - 100;

					if (scrollTop < holderTop) {
						$loader.addClass(hiddenClass + ' ' + hiddenTopClass);
					} else if (scrollTop > holderBottom) {
						$loader.addClass(hiddenClass + ' ' + hiddenBottomClass);
					} else {
						$loader.removeClass(hiddenClass + ' ' + hiddenTopClass + ' ' + hiddenBottomClass);
					}
				};

				var loaderHorizontalPosition = function () {
					var $products = $(contentClass),
						$sidebar = $(sidebarClass),
						$loader = $products.parent().find(loaderClass),
						sidebarWidth = $sidebar.outerWidth();

					if ($products.length < 1) return;

					if (sidebarWidth > 0 && $sidebar.hasClass(sidebarLeftClass)) {
						if ($('body').hasClass('rtl')) {
							$loader.css({
								'marginLeft': - sidebarWidth / 2 - 15
							})
						} else {
							$loader.css({
								'marginLeft': sidebarWidth / 2 - 15
							})
						}
					} else if (sidebarWidth > 0) {
						if ($('body').hasClass('rtl')) {
							$loader.css({
								'marginLeft': sidebarWidth / 2 - 15
							})
						} else {
							$loader.css({
								'marginLeft': - sidebarWidth / 2 - 15
							})
						}
					}

				};

				$(window).off('scroll.loaderVerticalPosition');
				$(window).off('loaderHorizontalPosition');

				$(window).on('scroll.loaderVerticalPosition', loaderVerticalPosition);
				$(window).on('resize.loaderHorizontalPosition', loaderHorizontalPosition);

				loaderVerticalPosition();
				loaderHorizontalPosition();

			},

            /**
             *--------------------------------------------------------------------------
             * Login sidebar
             *--------------------------------------------------------------------------
             */

			loginSidebar: function () {
				var body = $('body');

				$('.login-side-opener').on('click', function (e) {
					e.preventDefault();
					if (isOpened()) {
						closeWidget();
					} else {
						setTimeout(function () {
							openWidget();
						}, 10);
					}
				});

				body.on('click touchstart', '.basel-close-side', function () {
					if (isOpened()) closeWidget();
				});

				body.on('click', '.widget-close', function (e) {
					e.preventDefault();
					if (isOpened()) closeWidget();
				});

				var closeWidget = function () {
					body.removeClass('basel-login-side-opened');
				};

				var openWidget = function () {
					body.addClass('basel-login-side-opened');
				};

				var isOpened = function () {
					return body.hasClass('basel-login-side-opened');
				};

			},

            /**
             *--------------------------------------------------------------------------
             * Header banner
             *--------------------------------------------------------------------------
             */

			headerBanner: function () {
				var banner_version = basel_settings.header_banner_version,
					banner_btn = basel_settings.header_banner_close_btn,
					banner_enabled = basel_settings.header_banner_enabled;
				if (Cookies.get('basel_tb_banner_' + banner_version) == 'closed' || banner_btn == false || banner_enabled == false) return;
				var banner = $('.header-banner');

				if (!$('body').hasClass('page-template-maintenance')) {
					$('body').addClass('header-banner-display');
				}

				banner.on('click', '.close-header-banner', function (e) {
					e.preventDefault();
					closeBanner();
				})

				var closeBanner = function () {
					$('body').removeClass('header-banner-display').addClass('header-banner-hide');
					Cookies.set('basel_tb_banner_' + banner_version, 'closed', { expires: 60, path: '/' });
				};

			},

            /**
             *--------------------------------------------------------------------------
             * Hidden sidebar button
             *--------------------------------------------------------------------------
             */
			shopHiddenSidebar: function () {

				$('body').on('click', '.basel-show-sidebar-btn, .basel-sticky-sidebar-opener', function (e) {
					e.preventDefault();
					if ($('.sidebar-container').hasClass('show-hidden-sidebar')) {
						baselThemeModule.hideShopSidebar();
					} else {
						showSidebar();
					}
				});

				$('body').on('click touchstart', '.basel-close-side, .basel-close-sidebar-btn', function () {
					baselThemeModule.hideShopSidebar();
				});

				var showSidebar = function () {
					$('.sidebar-container').addClass('show-hidden-sidebar');
					$('body').addClass('basel-show-hidden-sidebar');
					$('.basel-show-sidebar-btn').addClass('btn-clicked');

					if ($(window).width() >= 1024) {
						$('.sidebar-inner.basel-sidebar-scroll').nanoScroller({
							paneClass: 'basel-scroll-pane',
							sliderClass: 'basel-scroll-slider',
							contentClass: 'basel-sidebar-content',
							preventPageScrolling: false
						});
					}
				};
			},

			hideShopSidebar: function () {
				$('.basel-show-sidebar-btn').removeClass('btn-clicked');
				$('.sidebar-container').removeClass('show-hidden-sidebar');
				$('body').removeClass('basel-show-hidden-sidebar');
				$('.sidebar-inner.basel-scroll').nanoScroller({ destroy: true });
			},

            /**
             *--------------------------------------------------------------------------
             * Mobile search icon 
             *--------------------------------------------------------------------------
             */

			mobileSearchIcon: function () {
				var body = $('body');
				$('.mobile-search-icon.search-button').on('click', function (e) {
					if ($(window).width() > 1024) return;

					e.preventDefault();
					if (!body.hasClass('act-mobile-menu')) {
						body.addClass('act-mobile-menu');
						$('.mobile-nav .searchform').find('input[type="text"]').focus();
					}
				});

			},

            /**
             *--------------------------------------------------------------------------
             * Content in popup element
             *--------------------------------------------------------------------------
             */

			contentPopup: function () {
				$('.basel-popup-with-content').magnificPopup({
					type: 'inline',
					removalDelay: 500, //delay removal by X to allow out-animation
					tClose: basel_settings.close,
					tLoading: basel_settings.loading,
					callbacks: {
						beforeOpen: function () {
							this.st.mainClass = baselTheme.popupEffect + ' content-popup-wrapper';
						},
						open: function () {
							$(document).trigger('basel-images-loaded');
						}
					}
				});
			},

			addToCartAllTypes: function () {
				if (basel_settings.ajax_add_to_cart == false) return;

				// AJAX add to cart for all types of products

				$('body').on('submit', 'form.cart', function (e) {
					var $productWrapper = $(this).parents('.single-product-page');
					if ($productWrapper.hasClass('product-type-external') || $productWrapper.hasClass('product-type-zakeke')) return;

					e.preventDefault();

					var $form = $(this),
						$thisbutton = $form.find('.single_add_to_cart_button'),
						data = $form.serialize();

					data += '&action=basel_ajax_add_to_cart';

					if ($thisbutton.val()) {
						data += '&add-to-cart=' + $thisbutton.val();
					}

					$thisbutton.removeClass('added not-added');
					$thisbutton.addClass('loading');

					// Trigger event
					$(document.body).trigger('adding_to_cart', [$thisbutton, data]);

					$.ajax({
						url: basel_settings.ajaxurl,
						data: data,
						method: 'POST',
						success: function (response) {
							if (!response) {
								return;
							}

							var this_page = window.location.toString();

							this_page = this_page.replace('add-to-cart', 'added-to-cart');

							if (response.error && response.product_url) {
								window.location = response.product_url;
								return;
							}

							// Redirect to cart option
							if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {

								window.location = wc_add_to_cart_params.cart_url;
								return;

							} else {

								$thisbutton.removeClass('loading');

								var fragments = response.fragments;
								var cart_hash = response.cart_hash;


								// Block fragments class
								if (fragments) {
									$.each(fragments, function (key) {
										$(key).addClass('updating');
									});
								}

								// Replace fragments
								if (fragments) {
									$.each(fragments, function (key, value) {
										$(key).replaceWith(value);
									});
								}

								// Show notices
								if (response.notices.indexOf('error') > 0) {
									if ($('.woocommerce-error').length > 0) $('.woocommerce-error').remove();
									$('.single-product-content').prepend(response.notices);
									$thisbutton.addClass('not-added');
								} else {
									if (basel_settings.add_to_cart_action == 'widget')
										$.magnificPopup.close();

									// Changes button classes
									$thisbutton.addClass('added');
									// Trigger event so themes can refresh other areas
									$(document.body).trigger('added_to_cart', [fragments, cart_hash, $thisbutton]);
								}

							}
						},
						error: function () {
							console.log('ajax adding to cart error');
						},
						complete: function () { },
					});

				});

			},


			initZoom: function () {
				var $mainGallery = $('.woocommerce-product-gallery__wrapper:not(.quick-view-gallery)');

				if (basel_settings.zoom_enable != 'yes') {
					return false;
				}

				$mainGallery.find('.woocommerce-product-gallery__image').each(function () {
					var $wrapper = $(this);
					var image = $wrapper.find('img');
					var zoomOptions = {
						touch: false
					};

					if ('ontouchstart' in window) {
						zoomOptions.on = 'click';
					}

					// But only zoom if the img is larger than its container.
					if (image.data('large_image_width') > $(this).width()) {
						$wrapper.trigger('zoom.destroy');
						$wrapper.zoom(zoomOptions);
					}
				});
			},

			videoPoster: function () {
				$('.basel-video-poster-wrapper').on('click', function () {
					var videoWrapper = $(this),
						video = videoWrapper.siblings('iframe'),
						videoScr = video.attr('src'),
						videoNewSrc = videoScr + '&autoplay=1';

					if (videoScr.indexOf('vimeo.com') + 1) {
						videoNewSrc = videoScr + '?autoplay=1';
					}
					video.attr('src', videoNewSrc);
					videoWrapper.addClass('hidden-poster');
				})
			},

			fixedHeaders: function () {

				var getHeaderHeight = function () {
					var headerHeight = header.outerHeight();

					if (body.hasClass('sticky-navigation-only')) {
						headerHeight = header.find('.navigation-wrap').outerHeight();
					}

					return headerHeight;
				};

				var headerSpacer = function () {
					if (stickyHeader.hasClass(headerStickedClass)) return;
					$('.header-spacing').height(getHeaderHeight()).css('marginBottom', 40);
				};

				var body = $("body"),
					header = $(".main-header"),
					stickyHeader = header,
					headerHeight = getHeaderHeight(),
					headerStickedClass = "act-scroll",
					stickyClasses = '',
					stickyStart = 0,
					links = header.find('.main-nav .menu>li>a');

				if (!body.hasClass('enable-sticky-header') || body.hasClass('global-header-vertical') || header.length == 0) return;

				var logo = header.find(".site-logo").clone().html(),
					navigation = header.find(".main-nav").clone().html(),
					rightColumn = header.find(".right-column").clone().html();

				var headerClone = [
					'<div class="sticky-header header-clone">',
					'<div class="container">',
					'<div class="site-logo">' + logo + '</div>',
					'<div class="main-nav site-navigation basel-navigation">' + navigation + '</div>',
					'<div class="right-column">' + rightColumn + '</div>',
					'</div>',
					'</div>',
				].join('');


				if ($('.topbar-wrapp').length > 0) {
					stickyStart = $('.topbar-wrapp').outerHeight();
				}

				if ($('.header-banner').length > 0 && body.hasClass('header-banner-display')) {
					stickyStart += $('.header-banner').outerHeight();
				}

				if (body.hasClass('sticky-header-real')) {
					var headerSpace = $('<div/>').addClass('header-spacing');
					header.before(headerSpace);

					$(window).on('resize', headerSpacer);

					var timeout;

					$(window).on("scroll", function (e) {
						if (body.hasClass('header-banner-hide')) {
							stickyStart = ($('.topbar-wrapp').length > 0) ? $('.topbar-wrapp').outerHeight() : 0;
						}
						if ($(this).scrollTop() > stickyStart) {
							stickyHeader.addClass(headerStickedClass);
						} else {
							stickyHeader.removeClass(headerStickedClass);
							clearTimeout(timeout);
							timeout = setTimeout(function () {
								headerSpacer();
							}, 200);
						}
					});

				} else if (body.hasClass('sticky-header-clone')) {
					header.before(headerClone);
					stickyHeader = $('.sticky-header');
				}

				// Change header height smooth on scroll
				if (body.hasClass('basel-header-smooth')) {

					$(window).on("scroll", function (e) {
						var space = (120 - $(this).scrollTop()) / 2;

						if (space >= 60) {
							space = 60;
						} else if (space <= 30) {
							space = 30;
						}
						links.css({
							paddingTop: space,
							paddingBottom: space
						});
					});

				}

				if (body.hasClass("basel-header-overlap") || body.hasClass('sticky-navigation-only')) {
				}


				if (!body.hasClass("basel-header-overlap") && body.hasClass("sticky-header-clone")) {
					header.attr('class').split(' ').forEach(function (el) {
						if (el.indexOf('main-header') == -1 && el.indexOf('header-') == -1) {
							stickyClasses += ' ' + el;
						}
					});

					stickyHeader.addClass(stickyClasses);

					stickyStart += headerHeight;

					$(window).on("scroll", function (e) {
						if (body.hasClass('header-banner-hide')) {
							stickyStart = $('.topbar-wrapp').outerHeight() + headerHeight;
						}
						if ($(this).scrollTop() > stickyStart) {
							stickyHeader.addClass(headerStickedClass);
						} else {
							stickyHeader.removeClass(headerStickedClass);
						}
					});
				}

				body.addClass('sticky-header-prepared');
			},

            /**
             *--------------------------------------------------------------------------
             * Vertical header
             *--------------------------------------------------------------------------
             */

			verticalHeader: function () {

				var $header = $('.header-vertical').first();

				if ($header.length < 1) return;

				var $body, $window, $sidebar, top = false,
					bottom = false, windowWidth, adminOffset, windowHeight, lastWindowPos = 0,
					topOffset = 0, bodyHeight, headerHeight, resizeTimer, Y = 0, delta,
					headerBottom, viewportBottom, scrollStep;

				$body = $(document.body);
				$window = $(window);
				adminOffset = $body.is('.admin-bar') ? $('#wpadminbar').height() : 0;

				$window
					.on('scroll', scroll)
					.on('resize', function () {
						clearTimeout(resizeTimer);
						resizeTimer = setTimeout(resizeAndScroll, 500);
					});

				resizeAndScroll();

				for (var i = 1; i < 6; i++) {
					setTimeout(resizeAndScroll, 100 * i);
				}


				// Sidebar scrolling.
				function resize() {
					windowWidth = $window.width();

					if (1024 > windowWidth) {
						top = bottom = false;
						$header.removeAttr('style');
					}
				}

				function scroll() {
					var windowPos = $window.scrollTop();

					if (1024 > windowWidth) {
						return;
					}

					headerHeight = $header.height();
					headerBottom = headerHeight + $header.offset().top;
					windowHeight = $window.height();
					bodyHeight = $body.height();
					viewportBottom = windowHeight + $window.scrollTop();
					delta = headerHeight - windowHeight;
					scrollStep = lastWindowPos - windowPos;

					// console.log('header bottom ', headerBottom);
					// console.log('viewport bottom ', viewportBottom);
					// console.log('Y ', Y);
					// console.log('delta  ', delta);
					// console.log('scrollStep  ', scrollStep);

					// If header height larger than window viewport
					if (delta > 0) {
						// Scroll down
						if (windowPos > lastWindowPos) {

							// If bottom overflow

							if (headerBottom > viewportBottom) {
								Y += scrollStep;
							}

							if (Y < -delta) {
								bottom = true;
								Y = -delta;
							}

							top = false;

						} else if (windowPos < lastWindowPos) { // Scroll up

							// If top overflow

							if ($header.offset().top < $window.scrollTop()) {
								Y += scrollStep;
							}

							if (Y >= 0) {
								top = true;
								Y = 0;
							}

							bottom = false;

						} else {

							if (headerBottom < viewportBottom) {
								Y = windowHeight - headerHeight;
							}

							if (Y >= 0) {
								top = true;
								Y = 0;
							}
						}
					} else {
						Y = 0;
					}

					// Change header Y coordinate
					$header.css({
						top: Y
					});

					lastWindowPos = windowPos;
				}

				function resizeAndScroll() {
					resize();
					scroll();
				}

			},


            /**
             *--------------------------------------------------------------------------
             * Split navigation header
             *--------------------------------------------------------------------------
             */

			splitNavHeader: function () {

				var header = $('.header-split');

				if (header.length <= 0) return;

				var navigation = header.find('.main-nav'),
					navItems = navigation.find('.menu > li'),
					itemsNumber = navItems.length,
					rtl = $('body').hasClass('rtl'),
					midIndex = parseInt(itemsNumber / 2 + 0.5 * rtl - .5),
					midItem = navItems.eq(midIndex),
					logo = header.find('.site-logo > .basel-logo-wrap'),
					logoWidth,
					leftWidth = 0,
					rule = (!rtl) ? 'marginRight' : 'marginLeft',
					rightWidth = 0;

				var recalc = function () {
					logoWidth = logo.outerWidth(),
						leftWidth = 5,
						rightWidth = 0;

					for (var i = itemsNumber - 1; i >= 0; i--) {
						var itemWidth = navItems.eq(i).outerWidth();
						if (i > midIndex) {
							rightWidth += itemWidth;
						} else {
							leftWidth += itemWidth;
						}
					};

					var diff = leftWidth - rightWidth;

					if (rtl) {
						if (leftWidth > rightWidth) {
							navigation.find('.menu > li:first-child').css('marginRight', -diff);
						} else {
							navigation.find('.menu > li:last-child').css('marginLeft', diff + 5);
						}
					} else {
						if (leftWidth > rightWidth) {
							navigation.find('.menu > li:last-child').css('marginRight', diff + 5);
						} else {
							navigation.find('.menu > li:first-child').css('marginLeft', -diff);
						}
					}

					midItem.css(rule, logoWidth);
				};

				logo.imagesLoaded(function () {
					recalc();
					header.addClass('menu-calculated');
				});

				$(window).on('resize', recalc);

				if (basel_settings.split_nav_fix) {
					$(window).on('scroll', function () {
						if (header.hasClass('act-scroll') && !header.hasClass('menu-sticky-calculated')) {
							recalc();
							header.addClass('menu-sticky-calculated');
							header.removeClass('menu-calculated');
						}
						if (!header.hasClass('act-scroll') && !header.hasClass('menu-calculated')) {
							recalc();
							header.addClass('menu-calculated');
							header.removeClass('menu-sticky-calculated');
						}
					});
				}

			},

            /**
             *--------------------------------------------------------------------------
             * Counter shortcode method
             *--------------------------------------------------------------------------
             */
			counterShortcode: function (counter) {
				if (counter.attr('data-state') == 'done' || counter.text() != counter.data('final')) {
					return;
				}
				counter.prop('Counter', 0).animate({
					Counter: counter.text()
				}, {
						duration: 3000,
						easing: 'swing',
						step: function (now) {
							if (now >= counter.data('final')) {
								counter.attr('data-state', 'done');
							}
							counter.text(Math.ceil(now));
						}
					});
			},

            /**
             *--------------------------------------------------------------------------
             * Activate methods in viewport
             *--------------------------------------------------------------------------
             */
			visibleElements: function () {

				$('.basel-counter .counter-value').each(function () {
					$(this).waypoint(function () {
						baselThemeModule.counterShortcode($(this));
					}, { offset: '100%' });
				});

			},

            /**
             *--------------------------------------------------------------------------
             * add class in wishlist
             *--------------------------------------------------------------------------
             */

			wishList: function () {
				var body = $("body");

				body.on("click", ".add_to_wishlist", function () {

					$(this).parent().addClass("feid-in");

				});

			},


            /**
             *--------------------------------------------------------------------------
             * Compare button
             *--------------------------------------------------------------------------
             */

			compare: function () {
				var body = $("body"),
					button = $("a.compare");

				body.on("click", "a.compare", function () {
					$(this).addClass("loading");
				});

				body.on("yith_woocompare_open_popup", function () {
					button.removeClass("loading");
					body.addClass("compare-opened");
				});

				body.on('click', '#cboxClose, #cboxOverlay', function () {
					body.removeClass("compare-opened");
				});

			},

			/**
			 *--------------------------------------------------------------------------
			 * Basel compare functions
			 *--------------------------------------------------------------------------
			 */
			baselCompare: function () {
				var cookiesName = 'basel_compare_list';
		
				if (basel_settings.is_multisite) {
					cookiesName += '_' + basel_settings.current_blog_id;
				}

				var $body = $("body"),
					$widget = $('.basel-compare-info-widget'),
					compareCookie = Cookies.get(cookiesName);

				if ($widget.length > 0) {
					try {
						var ids = JSON.parse(compareCookie);
						$widget.find('.compare-count').text(ids.length);
					} catch (e) {
						console.log('cant parse cookies json');
					}
				}
				// Add to compare action

				$body.on('click', '.basel-compare-btn a, a.basel-compare-btn', function (e) {
					var $this = $(this),
						id = $this.data('id'),
						addedText = $this.data('added-text');

					if ($this.hasClass('added')) return true;

					e.preventDefault();

					$this.addClass('loading');

					jQuery.ajax({
						url: basel_settings.ajaxurl,
						data: {
							action: 'basel_add_to_compare',
							id: id
						},
						dataType: 'json',
						method: 'GET',
						success: function (response) {
							if (response.table) {
								updateCompare(response);
							} else {
								console.log('something wrong loading compare data ', response);
							}
						},
						error: function (data) {
							console.log('We cant add to compare. Something wrong with AJAX response. Probably some PHP conflict.');
						},
						complete: function () {
							$this.removeClass('loading').addClass('added');

							if ($this.find('span').length > 0) {
								$this.find('span').text(addedText);
							} else {
								$this.text(addedText);
							}
						},
					});

				});

				// Remove from compare action

				$body.on('click', '.basel-compare-remove', function (e) {
					var $table = $('.basel-compare-table');

					e.preventDefault();
					var $this = $(this),
						id = $this.data('id');

					$table.addClass('loading');
					$this.addClass('loading');

					jQuery.ajax({
						url: basel_settings.ajaxurl,
						data: {
							action: 'basel_remove_from_compare',
							id: id
						},
						dataType: 'json',
						method: 'GET',
						success: function (response) {
							if (response.table) {
								updateCompare(response);
							} else {
								console.log('something wrong loading compare data ', response);
							}
						},
						error: function (data) {
							console.log('We cant remove product compare. Something wrong with AJAX response. Probably some PHP conflict.');
						},
						complete: function () {
							$table.removeClass('loading');
							$this.addClass('loading');
						},
					});

				});

				// Elements update after ajax

				function updateCompare(data) {
					if ($widget.length > 0) {
						$widget.find('.compare-count').text(data.count);
					}

					if ($('.basel-compare-table').length > 0) {
						$('.basel-compare-table').replaceWith(data.table);
					}

				}

			},

            /**
             *--------------------------------------------------------------------------
             * Promo popup
             *--------------------------------------------------------------------------
             */

			promoPopup: function () {
				var promo_version = basel_settings.promo_version;

				if ($('body').hasClass('page-template-maintenance') || basel_settings.enable_popup != 'yes' || (basel_settings.promo_popup_hide_mobile == 'yes' && $(window).width() < 768)) return;

				var popup = $('.basel-promo-popup'),
					shown = false,
					pages = Cookies.get('basel_shown_pages');

				var showPopup = function () {
					$.magnificPopup.open({
						items: {
							src: '.basel-promo-popup'
						},
						type: 'inline',
						removalDelay: 400, //delay removal by X to allow out-animation
						tClose: basel_settings.close,
						tLoading: basel_settings.loading,
						callbacks: {
							beforeOpen: function () {
								this.st.mainClass = 'basel-popup-effect';
							},
							open: function () {
								// Will fire when this exact popup is opened
								// this - is Magnific Popup object
							},
							close: function () {
								Cookies.set('basel_popup_' + promo_version, 'shown', { expires: 7, path: '/' });
							}
							// e.t.c.
						}
					});
					$(document).trigger('basel-images-loaded');
				};

				$('.basel-open-popup').on('click', function (e) {
					e.preventDefault();
					showPopup();
				})

				if (!pages) pages = 0;

				if (pages < basel_settings.popup_pages) {
					pages++;
					Cookies.set('basel_shown_pages', pages, { expires: 7, path: '/' });
					return false;
				}

				if (Cookies.get('basel_popup_' + promo_version) != 'shown') {
					if (basel_settings.popup_event == 'scroll') {
						$(window).scroll(function () {
							if (shown) return false;
							if ($(document).scrollTop() >= basel_settings.popup_scroll) {
								showPopup();
								shown = true;
							}
						});
					} else {
						setTimeout(function () {
							showPopup();
						}, basel_settings.popup_delay);
					}
				}

			},


            /**
             *--------------------------------------------------------------------------
             * Product video button
             *--------------------------------------------------------------------------
             */

			productVideo: function () {
				$('.product-video-button a').magnificPopup({
					tClose: basel_settings.close,
					tLoading: basel_settings.loading,
					type: 'iframe',
					iframe: {
						patterns: {
							youtube: {
								index: 'youtube.com/',
								id: 'v=',
								src: '//www.youtube.com/embed/%id%?rel=0&autoplay=1'
							}
						}
					},
					mainClass: 'mfp-fade',
					removalDelay: 160,
					preloader: false,
					disableOn: false,
					fixedContentPos: false
				});
			},


            /**
             *--------------------------------------------------------------------------
             * Product 360 button
             *--------------------------------------------------------------------------
             */

			product360Button: function () {
				$('.product-360-button a').magnificPopup({
					tClose: basel_settings.close,
					tLoading: basel_settings.loading,
					type: 'inline',
					mainClass: 'mfp-fade',
					removalDelay: 160,
					disableOn: false,
					preloader: false,
					fixedContentPos: false,
					callbacks: {
						open: function () {
							$(window).resize()
						},
					},

				});
			},

            /**
             *--------------------------------------------------------------------------
             * Cookies law
             *--------------------------------------------------------------------------
             */

			cookiesPopup: function () {
				var cookies_version = basel_settings.cookies_version;
				if (Cookies.get('basel_cookies_' + cookies_version) == 'accepted') return;
				var popup = $('.basel-cookies-popup');

				setTimeout(function () {
					popup.addClass('popup-display');
					popup.on('click', '.cookies-accept-btn', function (e) {
						e.preventDefault();
						acceptCookies();
					})
				}, 2500);

				var acceptCookies = function () {
					popup.removeClass('popup-display').addClass('popup-hide');
					Cookies.set('basel_cookies_' + cookies_version, 'accepted', { expires: 60, path: '/' });
				};
			},

            /**
             *--------------------------------------------------------------------------
             * Google map
             *--------------------------------------------------------------------------
             */

			googleMap: function () {
				var gmap = $(".google-map-container-with-content");

				$(window).resize(function () {
					gmap.css({
						'height': gmap.find('.basel-google-map.with-content').outerHeight()
					})
				});

			},

            /**
             *--------------------------------------------------------------------------
             * mobile responsive navigation
             *--------------------------------------------------------------------------
             */

			woocommerceWrappTable: function () {

				var wooTable = $(".woocommerce .shop_table:not(.wishlist_table)");

				var cartTotals = $(".woocommerce .cart_totals table");

				wooTable.wrap("<div class='responsive-table'></div>");

				cartTotals.wrap("<div class='responsive-table'></div>");

			},


            /**
             *--------------------------------------------------------------------------
             * Menu preparation
             *--------------------------------------------------------------------------
             */

			menuSetUp: function () {
				var mainMenu = $('.basel-navigation').find('ul.menu'),
					lis = mainMenu.find(' > li'),
					openedClass = 'item-menu-opened';

				mainMenu.on('click', ' > .item-event-click.menu-item-has-children > a', function (e) {
					e.preventDefault();
					if (!$(this).parent().hasClass(openedClass)) {
						$('.' + openedClass).removeClass(openedClass);
					}
					$(this).parent().toggleClass(openedClass);
				});

				$(document).on('click', function (e) {
					var target = e.target;
					if ($('.' + openedClass).length > 0 && !$(target).is('.item-event-hover') && !$(target).parents().is('.item-event-hover') && !$(target).parents().is('.' + openedClass + '')) {
						mainMenu.find('.' + openedClass + '').removeClass(openedClass);
						return false;
					}
				});

				var menuForIPad = function () {
					if ($(window).width() <= 1024) {
						mainMenu.find(' > .item-event-hover').each(function () {
							$(this).data('original-event', 'hover').removeClass('item-event-hover').addClass('item-event-click');
						});
					} else {
						mainMenu.find(' > .item-event-click').each(function () {
							if ($(this).data('original-event') == 'hover') {
								$(this).removeClass('item-event-click').addClass('item-event-hover');
							}
						});
					}
				};

				$(window).on('resize', menuForIPad);
			},
            /**
             *--------------------------------------------------------------------------
             * Keep navigation dropdowns in the screen
             *--------------------------------------------------------------------------
             */

			menuOffsets: function () {

				var $window = $(window),
					$header = $('.main-header'),
					mainMenu = $('.main-nav').find('ul.menu'),
					lis = mainMenu.find(' > li.menu-item-design-sized');


				mainMenu.on('hover', ' > li', function (e) {
					setOffset($(this));
				});

				var setOffset = function (li) {

					var dropdown = li.find(' > .sub-menu-dropdown'),
						siteWrapper = $('.website-wrapper');


					dropdown.attr('style', '');

					var dropdownWidth = dropdown.outerWidth(),
						dropdownOffset = dropdown.offset(),
						screenWidth = $window.width(),
						bodyRight = siteWrapper.outerWidth() + siteWrapper.offset().left,
						viewportWidth = ($('body').hasClass('wrapper-boxed') || $('body').hasClass('wrapper-boxed-small')) ? bodyRight : screenWidth;

					if (!dropdownWidth || !dropdownOffset) return;

					if ($('body').hasClass('rtl') && dropdownOffset.left <= 0 && li.hasClass('menu-item-design-sized') && !$header.hasClass('header-vertical')) {
						// If right point is not in the viewport
						var toLeft = - dropdownOffset.left;

						dropdown.css({
							right: - toLeft - 10
						});

						var beforeSelector = '.' + li.attr('class').split(' ').join('.') + '> .sub-menu-dropdown:before',
							arrowOffset = toLeft + li.width() / 2;

					} else if (dropdownOffset.left + dropdownWidth >= viewportWidth && li.hasClass('menu-item-design-sized') && !$header.hasClass('header-vertical')) {
						// If right point is not in the viewport
						var toRight = dropdownOffset.left + dropdownWidth - viewportWidth;

						dropdown.css({
							left: - toRight - 10
						});

						var beforeSelector = '.' + li.attr('class').split(' ').join('.') + '> .sub-menu-dropdown:before',
							arrowOffset = toRight + li.width() / 2;

					}

					// Vertical header fit
					if ($header.hasClass('header-vertical')) {

						var bottom = dropdown.offset().top + dropdown.outerHeight(),
							viewportBottom = $window.scrollTop() + $window.outerHeight();

						if (bottom > viewportBottom) {
							dropdown.css({
								top: viewportBottom - bottom - 10
							});
						}
					}
				};

				lis.each(function () {
					setOffset($(this));
					$(this).addClass('with-offsets');
				});

			},


            /**
             *--------------------------------------------------------------------------
             * One page menu
             *--------------------------------------------------------------------------
             */

			onePageMenu: function () {

				var scrollToRow = function (hash) {
					var row = $('#' + hash);

					if (row.length < 1) return;

					var position = row.offset().top;

					$('html, body').stop().animate({
						scrollTop: position - basel_settings.one_page_menu_offset
					}, 800, function () {
						activeMenuItem(hash);
					});
				};

				var activeMenuItem = function (hash) {
					var itemHash;
					$('.onepage-link').each(function () {
						itemHash = $(this).find('> a').attr('href').split('#')[1];

						if (itemHash == hash) {
							$('.onepage-link').removeClass('current-menu-item');
							$(this).addClass('current-menu-item');
						}

					});
				};

				$('body').on('click', '.onepage-link > a', function (e) {
					var $this = $(this),
						hash = $this.attr('href').split('#')[1];

					if ($('#' + hash).length < 1) return;

					e.preventDefault();

					scrollToRow(hash);

					// close mobile menu
					$('.basel-close-side').trigger('click');
				});

				if ($('.onepage-link').length > 0) {
					$('.entry-content > .vc_section, .entry-content > .vc_row').waypoint(function () {
						var hash = $(this).attr('id');
						activeMenuItem(hash);
					}, { offset: 0 });

					// $('.onepage-link').removeClass('current-menu-item');


					// URL contains hash
					var locationHash = window.location.hash.split('#')[1];

					if (window.location.hash.length > 1) {
						setTimeout(function () {
							scrollToRow(locationHash);
						}, 500);
					}

				}
			},


            /**
             *--------------------------------------------------------------------------
             * mobile responsive navigation
             *--------------------------------------------------------------------------
             */

			mobileNavigation: function () {

				var body = $("body"),
					mobileNav = $(".mobile-nav"),
					wrapperSite = $(".website-wrapper"),
					dropDownCat = $(".mobile-nav .site-mobile-menu .menu-item-has-children"),
					elementIcon = '<span class="icon-sub-menu"></span>',
					butOpener = $(".icon-sub-menu");

				dropDownCat.append(elementIcon);

				mobileNav.on("click", ".icon-sub-menu", function (e) {
					e.preventDefault();

					if ($(this).parent().hasClass("opener-page")) {
						$(this).parent().removeClass("opener-page").find("> ul").slideUp(200);
						$(this).parent().removeClass("opener-page").find(".sub-menu-dropdown .container > ul").slideUp(200);
						$(this).parent().find('> .icon-sub-menu').removeClass("up-icon");
					} else {
						$(this).parent().addClass("opener-page").find("> ul").slideDown(200);
						$(this).parent().addClass("opener-page").find(".sub-menu-dropdown .container > ul").slideDown(200);
						$(this).parent().find('> .icon-sub-menu').addClass("up-icon");
					}
				});


				body.on("click", ".mobile-nav-icon", function () {

					if (body.hasClass("act-mobile-menu")) {
						closeMenu();
					} else {
						openMenu();
					}

				});

				body.on("click touchstart", ".basel-close-side", function () {
					closeMenu();
				});

				body.on("click touchstart", ".mobile-nav .login-side-opener", function () {
					closeMenu();
				});

				function openMenu() {
					body.addClass("act-mobile-menu");
				}

				function closeMenu() {
					body.removeClass("act-mobile-menu");
				}
			},

            /**
             *--------------------------------------------------------------------------
             * Simple dropdown for category select on search form
             *--------------------------------------------------------------------------
             */
			simpleDropdown: function () {
				$('.input-dropdown-inner').each(function () {
					var dd = $(this);
					var btn = dd.find('> a');
					var input = dd.find('> input');
					var list = dd.find('> ul'); //.dd-list-wrapper

					$(document).on('click', function (e) {
						var target = e.target;
						if (dd.hasClass('dd-shown') && !$(target).is('.input-dropdown-inner') && !$(target).parents().is('.input-dropdown-inner')) {
							hideList();
							return false;
						}
					});

					btn.on('click', function (e) {
						e.preventDefault();

						if (dd.hasClass('dd-shown')) {
							hideList();
						} else {
							showList();
						}
						return false;
					});

					list.on('click', 'a', function (e) {
						e.preventDefault();
						var value = $(this).data('val');
						var label = $(this).text();
						list.find('.current-item').removeClass('current-item');
						$(this).parent().addClass('current-item');
						if (value != 0) {
							list.find('> li:first-child').show();
						} else if (value == 0) {
							list.find('> li:first-child').hide();
						}
						btn.text(label);
						input.val(value).trigger('cat_selected');
						hideList();
					});


					function showList() {
						dd.addClass('dd-shown');
						list.slideDown(100);

						// $(".dd-list-wrapper .basel-scroll").nanoScroller({
						//     paneClass: 'basel-scroll-pane',
						//     sliderClass: 'basel-scroll-slider',
						//     contentClass: 'basel-scroll-content',
						//     preventPageScrolling: false
						// });
					}

					function hideList() {
						dd.removeClass('dd-shown');
						list.slideUp(100);
					}
				});

			},

            /**
             *--------------------------------------------------------------------------
             * Function to make columns the same height
             *--------------------------------------------------------------------------
             */
			equalizeColumns: function () {

				$.fn.basel_equlize = function (options) {

					var settings = $.extend({
						child: "",
					}, options);

					var that = this;

					if (settings.child != '') {
						that = this.find(settings.child);
					}

					var resize = function () {

						var maxHeight = 0;
						var height;
						that.each(function () {
							$(this).attr('style', '');
							if ($(window).width() > 767 && $(this).outerHeight() > maxHeight)
								maxHeight = $(this).outerHeight();
						});

						that.each(function () {
							$(this).css({
								minHeight: maxHeight
							});
						});

					}

					$(window).on('resize', function () {
						resize();
					});
					setTimeout(function () {
						resize();
					}, 200);
					setTimeout(function () {
						resize();
					}, 500);
					setTimeout(function () {
						resize();
					}, 800);
				}

				$('.equal-columns').each(function () {
					$(this).basel_equlize({
						child: '> [class*=col-]'
					});
				});
			},


            /**
             *--------------------------------------------------------------------------
             * Enable masonry grid for blog
             *--------------------------------------------------------------------------
             */
			blogMasonry: function () {
				if (typeof ($.fn.isotope) == 'undefined' || typeof ($.fn.imagesLoaded) == 'undefined') return;
				var $container = $('.masonry-container');

				// initialize Masonry after all images have loaded
				$container.imagesLoaded(function () {
					$container.isotope({
						gutter: 0,
						isOriginLeft: !$('body').hasClass('rtl'),
						itemSelector: '.blog-design-masonry, .blog-design-mask, .masonry-item'
					});
				});

				$('.masonry-filter').on('click', 'a', function (e) {
					e.preventDefault();

					setTimeout(function () {
						jQuery(document).trigger('basel-images-loaded');
					}, 300);

					$('.masonry-filter').find('.filter-active').removeClass('filter-active');
					$(this).addClass('filter-active');
					var filterValue = $(this).attr('data-filter');
					$(this).parents('.portfolio-filter').siblings('.masonry-container.basel-portfolio-holder').first().isotope({
						filter: filterValue
					});
				});

			},


            /**
             *--------------------------------------------------------------------------
             * Load more button for blog shortcode
             *--------------------------------------------------------------------------
             */
			blogLoadMore: function () {

				$('.basel-blog-load-more').on('click', function (e) {
					e.preventDefault();

					var $this = $(this),
						holderId = $this.data('holder-id'),
						holder = $('.basel-blog-holder#' + holderId),
						atts = holder.data('atts'),
						paged = holder.data('paged');

					$this.addClass('loading');

					$.ajax({
						url: basel_settings.ajaxurl,
						data: {
							atts: atts,
							paged: paged,
							action: 'basel_get_blog_shortcode'
						},
						dataType: 'json',
						method: 'POST',
						success: function (data) {
							if (data.items) {
								if (holder.hasClass('masonry-container')) {
									// initialize Masonry after all images have loaded
									var items = $(data.items);
									holder.append(items).isotope('appended', items);
									holder.imagesLoaded().progress(function () {
										holder.isotope('layout');
									});
								} else {
									holder.append(data.items);
								}

								holder.data('paged', paged + 1);
							}

							if (data.status == 'no-more-posts') {
								$this.hide();
							}
						},
						error: function (data) {
							console.log('ajax error');
						},
						complete: function () {
							$this.removeClass('loading');
						},
					});

				});

			},


            /**
             *--------------------------------------------------------------------------
             * Load more button for products shortcode
             *--------------------------------------------------------------------------
             */
			productsLoadMore: function () {

				var process = false,
					intervalID;

				$('.basel-products-element').each(function () {
					var $this = $(this),
						cache = [],
						inner = $this.find('.basel-products-holder');

					if (!inner.hasClass('pagination-arrows') && !inner.hasClass('pagination-more-btn')) return;

					cache[1] = {
						items: inner.html(),
						status: 'have-posts'
					};

					$this.on('recalc', function () {
						calc();
					});

					if (inner.hasClass('pagination-arrows')) {
						$(window).resize(function () {
							calc();
						});
					}

					var calc = function () {
						var height = inner.outerHeight();
						$this.stop().css({ minHeight: height });
					};

					// sticky buttons

					var body = $('body'),
						btnWrap = $this.find('.products-footer'),
						btnLeft = btnWrap.find('.basel-products-load-prev'),
						btnRight = btnWrap.find('.basel-products-load-next'),
						loadWrapp = $this.find('.basel-products-loader'),
						scrollTop,
						holderTop,
						btnLeftOffset,
						btnRightOffset,
						holderBottom,
						holderHeight,
						holderWidth,
						btnsHeight,
						offsetArrow = 50,
						offset,
						windowWidth;

					if (body.hasClass('rtl')) {
						btnLeft = btnRight;
						btnRight = btnWrap.find('.basel-products-load-prev');
					}

					$(window).scroll(function () {
						buttonsPos();
					});

					function buttonsPos() {

						offset = $(window).height() / 2;

						windowWidth = $(window).outerWidth(true) + 17;

						holderWidth = $this.outerWidth(true) + 10;

						scrollTop = $(window).scrollTop();

						holderTop = $this.offset().top - offset;

						btnLeftOffset = $this.offset().left - offsetArrow;

						btnRightOffset = btnLeftOffset + holderWidth + offsetArrow;

						btnsHeight = btnLeft.outerHeight();

						holderHeight = $this.height() - 50 - btnsHeight;

						holderBottom = holderTop + holderHeight;

						if (windowWidth <= 1047 && windowWidth >= 992 || windowWidth <= 825 && windowWidth >= 768) {
							btnLeftOffset += 18;
							btnRightOffset -= 18;
						}

						if (windowWidth < 768 || body.hasClass('wrapper-boxed') || body.hasClass('wrapper-boxed-small') || $('.main-header').hasClass('header-vertical')) {
							btnLeftOffset += 51;
							btnRightOffset -= 51;
						}

						btnLeft.css({
							'left': btnLeftOffset + 'px'
						});

						// Right arrow position for vertical header
						// if( $('.main-header').hasClass('header-vertical') && ! body.hasClass('rtl') ) {
						//     btnRightOffset -= $('.main-header').outerWidth();
						// } else if( $('.main-header').hasClass('header-vertical') && body.hasClass('rtl') ) {
						//     btnRightOffset += $('.main-header').outerWidth();
						// }

						btnRight.css({
							'left': btnRightOffset + 'px'
						});


						if (scrollTop < holderTop || scrollTop > holderBottom) {
							btnWrap.removeClass('show-arrow');
							loadWrapp.addClass('hidden-loader');
						} else {
							btnWrap.addClass('show-arrow');
							loadWrapp.removeClass('hidden-loader');
						}

					};

					$this.find('.basel-products-load-prev, .basel-products-load-next').on('click', function (e) {
						e.preventDefault();

						if (process || $(this).hasClass('disabled')) return; process = true;

						clearInterval(intervalID);

						var $this = $(this),
							holder = $this.parent().siblings('.basel-products-holder'),
							next = $this.parent().find('.basel-products-load-next'),
							prev = $this.parent().find('.basel-products-load-prev'),
							atts = holder.data('atts'),
							paged = holder.attr('data-paged'),
							ajaxurl = basel_settings.ajaxurl,
							action = 'basel_get_products_shortcode',
							method = 'POST';

						if ($this.hasClass('basel-products-load-prev')) {
							if (paged < 2) return;
							paged = paged - 2;
						}

						paged++;

						loadProducts('arrows', atts, ajaxurl, action, method, paged, holder, $this, cache, function (data) {
							holder.addClass('basel-animated-products');

							if (data.items) {
								holder.html(data.items).attr('data-paged', paged);
								holder.imagesLoaded().progress(function () {
									holder.parent().trigger('recalc');
								});

								$(document).trigger('basel-images-loaded');

								baselThemeModule.btnsToolTips();
							}

							if ($(window).width() < 768) {
								$('html, body').stop().animate({
									scrollTop: holder.offset().top - 150
								}, 400);
							}


							var iter = 0;
							intervalID = setInterval(function () {
								holder.find('.product-grid-item').eq(iter).addClass('basel-animated');
								iter++;
							}, 100);

							if (paged > 1) {
								prev.removeClass('disabled');
							} else {
								prev.addClass('disabled');
							}

							if (data.status == 'no-more-posts') {
								next.addClass('disabled');
							} else {
								next.removeClass('disabled');
							}
						});

					});
				});

				baselThemeModule.clickOnScrollButton(baselTheme.shopLoadMoreBtn, false);

				$(document).off('click', '.basel-products-load-more').on('click', '.basel-products-load-more', function (e) {
					e.preventDefault();

					if (process) return; process = true;

					var $this = $(this),
						holder = $this.parent().siblings('.basel-products-holder'),
						source = holder.data('source'),
						action = 'basel_get_products_' + source,
						ajaxurl = basel_settings.ajaxurl,
						method = 'POST',
						atts = holder.data('atts'),
						paged = holder.data('paged');

					paged++;

					if (source == 'main_loop') {
						ajaxurl = $(this).attr('href');
						method = 'GET';
					}

					loadProducts('load-more', atts, ajaxurl, action, method, paged, holder, $this, [], function (data) {
						if (data.items) {
							if (holder.hasClass('grid-masonry')) {
								isotopeAppend(holder, data.items);
							} else {
								holder.append(data.items);
							}

							holder.imagesLoaded().progress(function () {
								baselThemeModule.clickOnScrollButton(baselTheme.shopLoadMoreBtn, true);
							});

							$(document).trigger('basel-images-loaded');

							holder.data('paged', paged);

							baselThemeModule.btnsToolTips();
						}

						if (source == 'main_loop') {
							$this.attr('href', data.nextPage);
							if (data.status == 'no-more-posts') {
								$this.hide().remove();
							}
						}

						if (data.status == 'no-more-posts') {
							$this.hide().remove();
						}
					});

				});

				var loadProducts = function (btnType, atts, ajaxurl, action, method, paged, holder, btn, cache, callback) {
					var data = {
						atts: atts,
						paged: paged,
						action: action,
						woo_ajax: 1
					};

					if (cache[paged]) {
						holder.addClass('loading');
						setTimeout(function () {
							callback(cache[paged]);
							holder.removeClass('loading');
							process = false;
						}, 300);
						return;
					}

					holder.addClass('loading').parent().addClass('element-loading');

					if (btnType == 'arrows') holder.addClass('loading').parent().addClass('element-loading');

					if (action == 'basel_get_products_main_loop') {
						var loop = holder.find('.product').last().data('loop');
						data = {
							loop: loop,
							woo_ajax: 1
						};
					}

					btn.addClass('loading');

					$.ajax({
						url: ajaxurl,
						data: data,
						dataType: 'json',
						method: method,
						success: function (data) {
							cache[paged] = data;
							callback(data);
						},
						error: function (data) {
							console.log('ajax error');
						},
						complete: function () {
							holder.removeClass('loading').parent().removeClass('element-loading');
							btn.removeClass('loading');
							process = false;
							baselThemeModule.compare();
							baselThemeModule.countDownTimer();
						},
					});
				};

				var isotopeAppend = function (el, items) {
					// initialize Masonry after all images have loaded
					var items = $(items);
					el.append(items).isotope('appended', items);
					el.isotope('layout');
					setTimeout(function () {
						el.isotope('layout');
					}, 100);
					el.imagesLoaded().progress(function () {
						el.isotope('layout');
					});
				};

			},

            /**
             *--------------------------------------------------------------------------
             * Helper function that make btn click when you scroll page to it
             *--------------------------------------------------------------------------
             */
			clickOnScrollButton: function (btnClass, destroy) {
				if (typeof $.waypoints != 'function') return;

				var $btn = $(btnClass);
				if (destroy) {
					$btn.waypoint('destroy');
				}

				$btn.waypoint(function () {
					$btn.trigger('click');
				}, { offset: '100%' });
			},

            /**
             *--------------------------------------------------------------------------
             * Products tabs element AJAX loading
             *--------------------------------------------------------------------------
             */
			productsTabs: function () {


				var process = false;

				$('.basel-products-tabs').each(function () {
					var $this = $(this),
						$inner = $this.find('.basel-tab-content'),
						cache = [];

					if ($inner.find('.owl-carousel').length < 1) {
						cache[0] = {
							html: $inner.html()
						};
					}

					$this.find('.products-tabs-title li').on('click', function (e) {
						e.preventDefault();

						var $this = $(this),
							atts = $this.data('atts'),
							index = $this.index();

						if (process || $this.hasClass('active-tab-title')) return; process = true;

						loadTab(atts, index, $inner, $this, cache, function (data) {
							if (data.html) {
								$inner.html(data.html);

								$(document).trigger('basel-images-loaded');

								baselThemeModule.btnsToolTips();
								baselThemeModule.shopMasonry();
								baselThemeModule.productsLoadMore();
								baselThemeModule.productLoaderPosition();
							}
						});

					});

					var $nav = $this.find('.tabs-navigation-wrapper'),
						$subList = $nav.find('ul'),
						time = 300;

					$nav.on('click', '.open-title-menu', function () {
						var $btn = $(this);

						if ($subList.hasClass('list-shown')) {
							$btn.removeClass('toggle-active');
							$subList.removeClass('list-shown');
						} else {
							$btn.addClass('toggle-active');
							$subList.addClass('list-shown');
							setTimeout(function () {
								$('body').one('click', function (e) {
									var target = e.target;
									if (!$(target).is('.tabs-navigation-wrapper') && !$(target).parents().is('.tabs-navigation-wrapper')) {
										$btn.removeClass('toggle-active');
										$subList.removeClass('list-shown');
										return false;
									}
								});
							}, 10);
						}

					})
						.on('click', 'li', function () {
							var $btn = $nav.find('.open-title-menu'),
								text = $(this).text();

							if ($subList.hasClass('list-shown')) {
								$btn.removeClass('toggle-active').text(text);
								$subList.removeClass('list-shown');
							}
						});

				});

				var loadTab = function (atts, index, holder, btn, cache, callback) {

					btn.parent().find('.active-tab-title').removeClass('active-tab-title');
					btn.addClass('active-tab-title')

					if (cache[index]) {
						holder.addClass('loading');
						setTimeout(function () {
							callback(cache[index]);
							holder.removeClass('loading');
							process = false;
						}, 300);
						return;
					}

					holder.addClass('loading').parent().addClass('element-loading');

					btn.addClass('loading');

					$.ajax({
						url: basel_settings.ajaxurl,
						data: {
							atts: atts,
							action: 'basel_get_products_tab_shortcode'
						},
						dataType: 'json',
						method: 'POST',
						success: function (data) {
							cache[index] = data;
							callback(data);
						},
						error: function (data) {
							console.log('ajax error');
						},
						complete: function () {
							holder.removeClass('loading').parent().removeClass('element-loading');
							btn.removeClass('loading');
							process = false;
							baselThemeModule.compare();
						},
					});
				};


			},


            /**
             *--------------------------------------------------------------------------
             * Load more button for portfolio shortcode
             *--------------------------------------------------------------------------
             */
			portfolioLoadMore: function () {

				if (typeof $.waypoints != 'function') return;

				var waypoint = $('.basel-portfolio-load-more.load-on-scroll').waypoint(function () {
					$('.basel-portfolio-load-more.load-on-scroll').trigger('click');
				}, { offset: '100%' }),
					process = false;

				$('.basel-portfolio-load-more').on('click', function (e) {
					e.preventDefault();

					if (process || $(this).hasClass('no-more-posts')) return;

					process = true;

					var $this = $(this),
						holder = $this.parent().parent().find('.basel-portfolio-holder'),
						source = holder.data('source'),
						action = 'basel_get_portfolio_' + source,
						ajaxurl = basel_settings.ajaxurl,
						dataType = 'json',
						method = 'POST',
						timeout,
						atts = holder.data('atts'),
						paged = holder.data('paged');

					$this.addClass('loading');

					var data = {
						atts: atts,
						paged: paged,
						action: action
					};

					if (source == 'main_loop') {
						ajaxurl = $(this).attr('href');
						method = 'GET';
						data = {};
					}


					$.ajax({
						url: ajaxurl,
						data: data,
						dataType: dataType,
						method: method,
						success: function (data) {

							var items = $(data.items);

							if (items) {
								if (holder.hasClass('masonry-container')) {
									// initialize Masonry after all images have loaded
									holder.append(items).isotope('appended', items);
									holder.imagesLoaded().progress(function () {
										holder.isotope('layout');

										clearTimeout(timeout);

										timeout = setTimeout(function () {
											$('.basel-portfolio-load-more.load-on-scroll').waypoint('destroy');
											waypoint = $('.basel-portfolio-load-more.load-on-scroll').waypoint(function () {
												$('.basel-portfolio-load-more.load-on-scroll').trigger('click');
											}, { offset: '100%' });
										}, 1000);
									});
								} else {
									holder.append(items);
								}

								holder.data('paged', paged + 1);

								$this.attr('href', data.nextPage);
							}

							baselThemeModule.mfpPopup();

							if (data.status == 'no-more-posts') {
								$this.addClass('no-more-posts');
								$this.hide();
							}

						},
						error: function (data) {
							console.log('ajax error');
						},
						complete: function () {
							$this.removeClass('loading');
							process = false;
						},
					});

				});

			},



            /**
             *--------------------------------------------------------------------------
             * Enable masonry grid for shop isotope type
             *--------------------------------------------------------------------------
             */
			shopMasonry: function () {
				if (typeof ($.fn.isotope) == 'undefined' || typeof ($.fn.imagesLoaded) == 'undefined') return;
				var $container = $('.elements-grid.grid-masonry');
				// initialize Masonry after all images have loaded
				$container.imagesLoaded(function () {
					$container.isotope({
						isOriginLeft: !$('body').hasClass('rtl'),
						itemSelector: '.category-grid-item, .product-grid-item',
					});
				});

				// Categories masonry
				$(window).resize(function () {
					var $catsContainer = $('.categories-masonry');
					var colWidth = ($catsContainer.hasClass('categories-style-masonry')) ? '.category-grid-item' : '.col-md-3.category-grid-item';
					$catsContainer.imagesLoaded(function () {
						$catsContainer.packery({
							resizable: false,
							isOriginLeft: !$('body').hasClass('rtl'),
							// layoutMode: 'packery',
							packery: {
								gutter: 0,
								columnWidth: colWidth
							},
							itemSelector: '.category-grid-item',
							// masonry: {
							// gutter: 0
							// }
						});
					});
				});

			},

            /**
             *--------------------------------------------------------------------------
             * MEGA MENU
             *--------------------------------------------------------------------------
             */
			sidebarMenu: function () {
				var heightMegaMenu = $(".widget_nav_mega_menu").height();
				var heightMegaNavigation = $(".categories-menu-dropdown").height();
				var subMenuHeight = $(".widget_nav_mega_menu ul > li.menu-item-design-sized > .sub-menu-dropdown, .widget_nav_mega_menu ul > li.menu-item-design-full-width > .sub-menu-dropdown");
				var megaNavigationHeight = $(".categories-menu-dropdown ul > li.menu-item-design-sized > .sub-menu-dropdown, .categories-menu-dropdown ul > li.menu-item-design-full-width > .sub-menu-dropdown");
				subMenuHeight.css(
					"min-height", heightMegaMenu + "px"
				);

				megaNavigationHeight.css(
					"min-height", heightMegaNavigation + "px"
				);
			},


            /**
             *--------------------------------------------------------------------------
             * Product thumbnail images & photo swipe gallery
             *--------------------------------------------------------------------------
             */
			productImages: function () {


				// Init photoswipe

				var currentImage,
					$productGallery = $('.woocommerce-product-gallery'),
					$mainImages = $('.woocommerce-product-gallery__wrapper'),
					$thumbs = $productGallery.find('.thumbnails'),
					currentClass = 'current-image',
					gallery = $('.photoswipe-images'),
					PhotoSwipeTrigger = '.basel-show-product-gallery',
					galleryType = 'photo-swipe'; // magnific photo-swipe

				$thumbs.addClass('thumbnails-ready');

				if ($productGallery.hasClass('image-action-popup')) {
					PhotoSwipeTrigger += ', .woocommerce-product-gallery__image a';
				}

				$productGallery.on('click', '.woocommerce-product-gallery__image a', function (e) {
					e.preventDefault();
				});

				$productGallery.on('click', PhotoSwipeTrigger, function (e) {
					e.preventDefault();

					currentImage = $(this).attr('href');

					if (galleryType == 'magnific') {
						$.magnificPopup.open({
							type: 'image',
							tClose: basel_settings.close,
							tLoading: basel_settings.loading,
							image: {
								verticalFit: false
							},
							items: getProductItems(),
							gallery: {
								enabled: true,
								navigateByImgClick: false
							},
						}, 0);
					}

					if (galleryType == 'photo-swipe') {

						// build items array
						var items = getProductItems();

						baselThemeModule.callPhotoSwipe(getCurrentGalleryIndex(e), items);

					}

				});

				$thumbs.on('click', '.image-link', function (e) {
					e.preventDefault();

					// if( $thumbs.hasClass('thumbnails-large') ) {
					//     var index = $(e.currentTarget).index() + 1;
					//     var items = getProductItems();
					//     callPhotoSwipe(index, items);
					//     return;
					// }

					// var href = $(this).attr('href'),
					//     src  = $(this).attr('data-single-image'),
					//     width = $(this).attr('data-width'),
					//     height = $(this).attr('data-height'),
					//     title = $(this).attr('title');

					// $thumbs.find('.' + currentClass).removeClass(currentClass);
					// $(this).addClass(currentClass);

					// if( $mainImages.find('img').attr('src') == src ) return;

					// $mainImages.addClass('loading-image').attr('href', href).find('img').attr('src', src).attr('srcset', src).one('load', function() {
					//     $mainImages.removeClass('loading-image').data('width', width).data('height', height).attr('title', title);
					// });

				});

				gallery.each(function () {
					var $this = $(this);
					$this.on('click', 'a', function (e) {
						e.preventDefault();
						var index = $(e.currentTarget).data('index') - 1;
						var items = getGalleryItems($this, []);
						baselThemeModule.callPhotoSwipe(index, items);
					});
				})

				var getCurrentGalleryIndex = function (e) {
					if ($mainImages.hasClass('owl-carousel'))
						return $mainImages.find('.owl-item.active').index();
					else return $(e.currentTarget).parent().index();
				};

				var getProductItems = function () {
					var items = [];

					$mainImages.find('figure a img').each(function () {
						var src = $(this).attr('data-large_image'),
							width = $(this).attr('data-large_image_width'),
							height = $(this).attr('data-large_image_height'),
							caption = $(this).data('caption');

						items.push({
							src: src,
							w: width,
							h: height,
							title: (basel_settings.product_images_captions == 'yes') ? caption : false
						});

					});

					return items;
				};

				var getGalleryItems = function ($gallery, items) {
					var src, width, height, title;

					$gallery.find('a').each(function () {
						src = $(this).attr('href');
						width = $(this).data('width');
						height = $(this).data('height');
						title = $(this).attr('title');
						if (!isItemInArray(items, src)) {
							items.push({
								src: src,
								w: width,
								h: height,
								title: title
							});
						}
					});

					return items;
				};

				var isItemInArray = function (items, src) {
					var i;
					for (i = 0; i < items.length; i++) {
						if (items[i].src == src) {
							return true;
						}
					}

					return false;
				};

				/* Fix zoom for first item firstly */

				if ($productGallery.hasClass('image-action-zoom')) {
					var zoom_target = $('.woocommerce-product-gallery__image');
					var image_to_zoom = zoom_target.find('img');

					// But only zoom if the img is larger than its container.
					if (image_to_zoom.attr('width') > $('.woocommerce-product-gallery').width()) {
						zoom_target.trigger('zoom.destroy');
						zoom_target.zoom({
							touch: false
						});
					}
				}

			},

			callPhotoSwipe: function (index, items) {
				var pswpElement = document.querySelectorAll('.pswp')[0];

				if ($('body').hasClass('rtl')) {
					index = items.length - index - 1;
					items = items.reverse();
				}

				// define options (if needed)
				var options = {
					// optionName: 'option value'
					// for example:
					index: index, // start at first slide
					shareButtons: [
						{ id: 'facebook', label: basel_settings.share_fb, url: 'https://www.facebook.com/sharer/sharer.php?u={{url}}' },
						{ id: 'twitter', label: basel_settings.tweet, url: 'https://twitter.com/intent/tweet?text={{text}}&url={{url}}' },
						{
							id: 'pinterest', label: basel_settings.pin_it, url: 'http://www.pinterest.com/pin/create/button/' +
								'?url={{url}}&media={{image_url}}&description={{text}}'
						},
						{ id: 'download', label: basel_settings.download_image, url: '{{raw_image_url}}', download: true }
					],
					getThumbBoundsFn: function (index) {

						// // get window scroll Y
						// var pageYScroll = window.pageYOffset || document.documentElement.scrollTop;
						// // optionally get horizontal scroll

						// // get position of element relative to viewport
						// var rect = $target.offset();

						// // w = width
						// return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};

					}
				};

				// Initializes and opens PhotoSwipe
				var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
				gallery.init();
			},

			productImagesGallery: function () {
				var $mainGallery = $('.woocommerce-product-gallery__wrapper:not(.quick-view-gallery)');
				var $mainImages = $('.woocommerce-product-gallery__image:eq(0) img'),
					$thumbs = $('.images .thumbnails'), // magnific photo-swipe
					$mainOwl = $('.woocommerce-product-gallery__wrapper');

				if (basel_settings.product_gallery.images_slider) {
					if (basel_settings.product_slider_auto_height == 'yes') {
						$('.product-images').imagesLoaded(function () {
							initMainGallery();
						});
					} else {
						initMainGallery();
					}
				}

				if (basel_settings.product_gallery.thumbs_slider.enabled && basel_settings.product_gallery.images_slider) {
					initThumbnailsMarkup();
					if (basel_settings.product_gallery.thumbs_slider.position == 'left' && jQuery(window).width() > 991) {
						initThumbnailsVertical();
					} else {
						initThumbnailsHorizontal();
					}
				}


				function initMainGallery() {
					$mainGallery.trigger('destroy.owl.carousel');
					$mainGallery.addClass('owl-carousel').owlCarousel(baselTheme.mainCarouselArg);
					$(document).trigger('basel-images-loaded');
				};

				function initThumbnailsMarkup() {
					var markup = '';

					$mainGallery.find('.woocommerce-product-gallery__image').each(function () {
						var image = $(this).data('thumb'),
							alt = $(this).find('a > img').attr('alt'),
							title = $(this).find('a > img').attr('title');

						markup += '<img alt="' + alt + '" title="' + title + '" src="' + image + '" />';
					});

					if ($thumbs.hasClass('slick-slider')) {
						$thumbs.slick('unslick');
					} else if ($thumbs.hasClass('owl-carousel')) {
						$thumbs.trigger('destroy.owl.carousel');
					}

					$thumbs.empty();
					$thumbs.append(markup);

				};

				function initThumbnailsVertical() {
					$thumbs.slick({
						slidesToShow: basel_settings.product_gallery.thumbs_slider.items.vertical_items,
						slidesToScroll: basel_settings.product_gallery.thumbs_slider.items.vertical_items,
						vertical: true,
						verticalSwiping: true,
						infinite: false,
					});

					$thumbs.on('click', 'img', function (e) {
						var i = $(this).index();
						$mainOwl.trigger('to.owl.carousel', i);
					});

					$mainOwl.on('changed.owl.carousel', function (e) {
						var i = e.item.index;
						$thumbs.slick('slickGoTo', i);
						$thumbs.find('.active-thumb').removeClass('active-thumb');
						$thumbs.find('img').eq(i).addClass('active-thumb');
					});

					$thumbs.find('img').eq(0).addClass('active-thumb');
				};

				function initThumbnailsHorizontal() {
					$thumbs.addClass('owl-carousel').owlCarousel({
						rtl: $('body').hasClass('rtl'),
						items: basel_settings.product_gallery.thumbs_slider.items.desktop,
						responsive: {
							979: {
								items: basel_settings.product_gallery.thumbs_slider.items.desktop
							},
							768: {
								items: basel_settings.product_gallery.thumbs_slider.items.desktop_small
							},
							479: {
								items: basel_settings.product_gallery.thumbs_slider.items.tablet
							},
							0: {
								items: basel_settings.product_gallery.thumbs_slider.items.mobile
							}
						},
						dots: false,
						nav: true,
						// mouseDrag: false,
						navText: false,
					});

					var $thumbsOwl = $thumbs.owlCarousel();

					$thumbs.on('click', '.owl-item', function (e) {
						var i = $(this).index();
						$thumbsOwl.trigger('to.owl.carousel', i);
						$mainOwl.trigger('to.owl.carousel', i);
					});

					$mainOwl.on('changed.owl.carousel', function (e) {
						var i = e.item.index;
						$thumbsOwl.trigger('to.owl.carousel', i);
						$thumbs.find('.active-thumb').removeClass('active-thumb');
						$thumbs.find('.owl-item').eq(i).addClass('active-thumb');
					});

					$thumbs.find('.owl-item').eq(0).addClass('active-thumb');
				};

				// Update first thumbnail on variation change

			},


            /**
             *--------------------------------------------------------------------------
             * Sticky details block for special product type
             *--------------------------------------------------------------------------
             */
			stickyDetails: function () {
				if (!$('body').hasClass('basel-product-design-sticky')) return;

				var details = $('.entry-summary'),
					detailsInner = details.find('.summary-inner'),
					detailsWidth = details.width(),
					images = $('.product-images'),
					thumbnails = images.find('.woocommerce-product-gallery__wrapper a'),
					offsetThumbnils,
					viewportHeight = $(window).height(),
					imagesHeight = images.outerHeight(),
					topOffset = 130,
					maxWidth = 600,
					innerWidth,
					detailsHeight = details.outerHeight(),
					scrollTop = $(window).scrollTop(),
					imagesTop = images.offset().top,
					detailsLeft = details.offset().left + 15,
					imagesBottom = imagesTop + imagesHeight,
					detailsBottom = scrollTop + topOffset + detailsHeight;


				details.css({
					height: detailsHeight
				});

				$(window).resize(function () {
					recalculate();
				});

				$(window).scroll(function () {
					onscroll();
					animateThumbnails();
				});

				images.imagesLoaded(function () {
					recalculate();
				});


				function animateThumbnails() {
					viewportHeight = $(window).height();

					thumbnails.each(function () {
						offsetThumbnils = $(this).offset().top;

						if (scrollTop > (offsetThumbnils - viewportHeight + 20)) {
							$(this).addClass('animate-images');
						}

					});
				}

				function onscroll() {
					scrollTop = $(window).scrollTop();
					detailsBottom = scrollTop + topOffset + detailsHeight;
					detailsWidth = details.width();
					detailsLeft = details.offset().left + 15;
					imagesTop = images.offset().top;
					imagesBottom = imagesTop + imagesHeight;

					if (detailsWidth > maxWidth) {
						innerWidth = (detailsWidth - maxWidth) / 2;
						detailsLeft = detailsLeft + innerWidth;
					}

					// Fix after scroll the header
					if (scrollTop + topOffset >= imagesTop) {
						details.addClass('block-sticked');

						detailsInner.css({
							top: topOffset,
							left: detailsLeft,
							width: detailsWidth,
							position: 'fixed',
							transform: 'translateY(-20px)'
						});
					} else {
						details.removeClass('block-sticked');
						detailsInner.css({
							top: 'auto',
							left: 'auto',
							width: 'auto',
							position: 'relative',
							transform: 'translateY(0px)'
						});
					}



					// When rich the bottom line
					if (detailsBottom > imagesBottom) {
						details.addClass('hide-temporary');
					} else {
						details.removeClass('hide-temporary');
					}
				};


				function recalculate() {
					viewportHeight = $(window).height();
					detailsHeight = details.outerHeight();
					imagesHeight = images.outerHeight();

					// If enought space in the viewport
					if (detailsHeight < (viewportHeight - topOffset)) {
						details.addClass('in-viewport').removeClass('not-in-viewport');
					} else {
						details.removeClass('in-viewport').addClass('not-in-viewport');
					}
				};

			},


            /**
             *--------------------------------------------------------------------------
             * Use magnific popup for images
             *--------------------------------------------------------------------------
             */
			mfpPopup: function () {
                /*$('.image-link').magnificPopup({
                    type:'image'
                });*/

				$('.gallery').magnificPopup({
					tClose: basel_settings.close,
					tLoading: basel_settings.loading,
					delegate: ' > a',
					type: 'image',
					image: {
						verticalFit: true
					},
					gallery: {
						enabled: true,
						navigateByImgClick: true
					},
				});

				$('[data-rel="mfp"]').magnificPopup({
					tClose: basel_settings.close,
					tLoading: basel_settings.loading,
					type: 'image',
					image: {
						verticalFit: true
					},
					gallery: {
						enabled: false,
						navigateByImgClick: false
					},
				});

				$(document).on('click', '.mfp-img', function () {
					var mfp = jQuery.magnificPopup.instance; // get instance
					mfp.st.image.verticalFit = !mfp.st.image.verticalFit; // toggle verticalFit on and off
					mfp.currItem.img.removeAttr('style'); // remove style attribute, to remove max-width if it was applied
					mfp.updateSize(); // force update of size
				});
			},


            /**
             *--------------------------------------------------------------------------
             * WooCommerce adding to cart
             *--------------------------------------------------------------------------
             */
			addToCart: function () {
				var that = this;
				var timeoutNumber = 0;
				var timeout;

				$('body').on('added_to_cart', function (event, fragments, cart_hash) {

					if (basel_settings.add_to_cart_action == 'popup') {

						var html = [
							'<div class="added-to-cart">',
							'<p>' + basel_settings.added_to_cart + '</p>',
							'<a href="#" class="btn btn-style-link close-popup">' + basel_settings.continue_shopping + '</a>',
							'<a href="' + basel_settings.cart_url + '" class="btn btn-color-primary view-cart">' + basel_settings.view_cart + '</a>',
							'</div>',
						].join("");

						$.magnificPopup.open({
							tClose: basel_settings.close,
							tLoading: basel_settings.loading,
							removalDelay: 500, //delay removal by X to allow out-animation
							callbacks: {
								beforeOpen: function () {
									this.st.mainClass = baselTheme.popupEffect + '  cart-popup-wrapper';
								},
							},
							items: {
								src: '<div class="white-popup add-to-cart-popup mfp-with-anim popup-added_to_cart">' + html + '</div>',
								type: 'inline'
							}
						});

						$('.white-popup').on('click', '.close-popup', function (e) {
							e.preventDefault();
							$.magnificPopup.close();
						});

						closeAfterTimeout();

					} else if (basel_settings.add_to_cart_action == 'widget') {

						clearTimeout(timeoutNumber);

						var currentHeader = ($('.sticky-header.act-scroll').length > 0) ? $('.sticky-header .dropdown-wrap-cat') : $('.main-header .dropdown-wrap-cat');

						if ($('.cart-widget-opener a').length > 0) {
							$('.cart-widget-opener a').trigger('click');
						} else if ($('.shopping-cart .a').length > 0) {
							$('.shopping-cart .dropdown-wrap-cat').addClass('display-widget');
							timeoutNumber = setTimeout(function () {
								$('.display-widget').removeClass('display-widget');
							}, 3500);
						} else {
							currentHeader.addClass('display-widget');
							timeoutNumber = setTimeout(function () {
								$('.display-widget').removeClass('display-widget');
							}, 3500);
						}

						closeAfterTimeout();
					}

					that.btnsToolTips();

				});

				var closeAfterTimeout = function () {
					if ('yes' !== basel_settings.add_to_cart_action_timeout) {
						return false;
					}

					clearTimeout(timeout);

					timeout = setTimeout(function () {
						$('.basel-close-side').trigger('click');
						$.magnificPopup.close();
					}, parseInt(basel_settings.add_to_cart_action_timeout_number) * 1000);
				};
			},

			updateWishListNumberInit: function () {

				if (basel_settings.wishlist == 'no' || $('.wishlist-count').length <= 0) return;

				var that = this;

				if (baselTheme.supports_html5_storage) {

					try {
						var wishlistNumber = sessionStorage.getItem('basel_wishlist_number'),
							cookie_hash = Cookies.get('basel_wishlist_hash');


						if (wishlistNumber === null || wishlistNumber === undefined || wishlistNumber === '') {
							wishlistNumber = 0;
						}

						if (cookie_hash === null || cookie_hash === undefined || cookie_hash === '') {
							cookie_hash = 0;
						}

						if (wishlistNumber == cookie_hash) {
							this.setWishListNumber(wishlistNumber);
						} else {
							throw 'No wishlist number';
						}

					} catch (err) {
						this.updateWishListNumber();
					}

				} else {
					this.updateWishListNumber();
				}

				$('body').on('added_to_cart added_to_wishlist removed_from_wishlist', function () {
					that.updateWishListNumber();
					that.btnsToolTips();
					that.woocommerceWrappTable();
				});

			},

			updateCartWidgetFromLocalStorage: function () {

				var that = this;

				if (baselTheme.supports_html5_storage) {

					try {
						var wc_fragments = $.parseJSON(sessionStorage.getItem(wc_cart_fragments_params.fragment_name));

						if (wc_fragments && wc_fragments['div.widget_shopping_cart_content']) {

							$.each(wc_fragments, function (key, value) {
								$(key).replaceWith(value);
							});

							$(document.body).trigger('wc_fragments_loaded');
						} else {
							throw 'No fragment';
						}

					} catch (err) {
						console.log('cant update cart widget');
					}
				}

			},

			updateWishListNumber: function () {
				var that = this;
				$.ajax({
					url: basel_settings.ajaxurl,
					data: {
						action: 'basel_wishlist_number'
					},
					method: 'get',
					success: function (data) {
						that.setWishListNumber(data);
						if (baselTheme.supports_html5_storage) {
							sessionStorage.setItem('basel_wishlist_number', data);
						}
					}
				});
			},

			setWishListNumber: function (num) {
				num = ($.isNumeric(num)) ? num : 0;
				$('.wishlist-info-widget .wishlist-count').text(num);
			},


            /**
             *--------------------------------------------------------------------------
             * Side shopping cart widget
             *--------------------------------------------------------------------------
             */
			cartWidget: function () {
				var widget = $('.cart-widget-opener'),
					btn = widget.find('a'),
					body = $('body');

				widget.on('click', 'a', function (e) {
					if (!isCart() && !isCheckout()) e.preventDefault();

					if (isOpened()) {
						closeWidget();
					} else {
						setTimeout(function () {
							openWidget();
						}, 10);
					}

				});

				body.on("click touchstart", ".basel-close-side", function () {
					if (isOpened()) {
						closeWidget();
					}
				});

				body.on("click", ".widget-close", function (e) {
					e.preventDefault();
					if (isOpened()) {
						closeWidget();
					}
				});

				$(document).keyup(function (e) {
					if (e.keyCode === 27 && isOpened()) closeWidget();
				});

				var closeWidget = function () {
					$('body').removeClass('basel-cart-opened');
				};

				var openWidget = function () {
					if (isCart() || isCheckout()) return false;
					$('body').addClass('basel-cart-opened');
				};

				var isOpened = function () {
					return $('body').hasClass('basel-cart-opened');
				};

				var isCart = function () {
					return $('body').hasClass('woocommerce-cart');
				};

				var isCheckout = function () {
					return $('body').hasClass('woocommerce-checkout');
				};

				$(document).on('wc_fragments_refreshed wc_fragments_loaded added_to_cart', function () {
					$(document).trigger('basel-images-loaded');
				});
			},

            /**
             *--------------------------------------------------------------------------
             * Banner hover effect with jquery panr
             *--------------------------------------------------------------------------
             */
			bannersHover: function () {
				$(".promo-banner.hover-4").panr({
					sensitivity: 20,
					scale: false,
					scaleOnHover: true,
					scaleTo: 1.15,
					scaleDuration: .34,
					panY: true,
					panX: true,
					panDuration: 0.5,
					resetPanOnMouseLeave: true
				});
			},

            /**
             *--------------------------------------------------------------------------
             * Parallax effect
             *--------------------------------------------------------------------------
             */
			parallax: function () {
				$('.parallax-yes').each(function () {
					var $bgobj = $(this);
					$(window).scroll(function () {
						var yPos = -($(window).scrollTop() / $bgobj.data('speed'));
						var coords = 'center ' + yPos + 'px';
						$bgobj.css({
							backgroundPosition: coords
						});
					});
				});

				$('.basel-parallax').each(function () {
					var $this = $(this);
					if ($this.hasClass('wpb_column')) {
						$this.find('> .vc_column-inner').parallax("50%", 0.3);
					} else {
						$this.parallax("50%", 0.3);
					}
				});

			},


            /**
             *--------------------------------------------------------------------------
             * Scroll top button
             *--------------------------------------------------------------------------
             */
			scrollTop: function () {
				//Check to see if the window is top if not then display button
				$(window).scroll(function () {
					if ($(this).scrollTop() > 100) {
						$('.scrollToTop').addClass('button-show');
					} else {
						$('.scrollToTop').removeClass('button-show');
					}
				});

				//Click event to scroll to top
				$('.scrollToTop').on('click', function () {
					$('html, body').animate({
						scrollTop: 0
					}, 800);
					return false;
				});
			},


            /**
             *--------------------------------------------------------------------------
             * Quick View
             *--------------------------------------------------------------------------
             */
			quickViewInit: function () {
				var that = this;
				// Open popup with product info when click on Quick View button
				$(document).on('click', '.open-quick-view', function (e) {

					e.preventDefault();

					if ($('.open-quick-view').hasClass('loading')) {
						return true;
					}

					var productId = $(this).data('id'),
						loopName = $(this).data('loop-name'),
						loop = $(this).data('loop'),
						prev = '',
						next = '',
						loopBtns = $('.quick-view').find('[data-loop-name="' + loopName + '"]'),
						btn = $(this);

					btn.addClass('loading');

					if (typeof loopBtns[loop - 1] != 'undefined') {
						prev = loopBtns.eq(loop - 1).addClass('quick-view-prev');
						prev = $('<div>').append(prev.clone()).html();
					}

					if (typeof loopBtns[loop + 1] != 'undefined') {
						next = loopBtns.eq(loop + 1).addClass('quick-view-next');
						next = $('<div>').append(next.clone()).html();
					}

					that.quickViewLoad(productId, btn, prev, next);

				});
			},

			quickViewCarousel: function () {
				$('.product-quick-view .woocommerce-product-gallery__wrapper').trigger('destroy.owl.carousel');
				$('.product-quick-view .woocommerce-product-gallery__wrapper').addClass('owl-carousel').owlCarousel({
					rtl: $('body').hasClass('rtl'),
					items: 1,
					dots: false,
					nav: true,
					navText: false
				});
			},

			quickViewLoad: function (id, btn, prev, next) {
				var data = {
					id: id,
					action: "basel_quick_view"
				};

				var initPopup = function (data) {
					// Open directly via API
					$.magnificPopup.open({
						items: {
							src: '<div class="mfp-with-anim white-popup popup-quick-view">' + data + '</div>', // can be a HTML string, jQuery object, or CSS selector
							type: 'inline',
						},
						tClose: basel_settings.close,
						tLoading: basel_settings.loading,
						removalDelay: 500, //delay removal by X to allow out-animation
						callbacks: {
							beforeOpen: function () {
								this.st.mainClass = baselTheme.popupEffect;
							},
							open: function () {
								$('.variations_form').each(function () {
									$(this).wc_variation_form().find('.variations select:eq(0)').change();
								});
								$('.variations_form').trigger('wc_variation_form');
								$('body').trigger('basel-quick-view-displayed');
								baselThemeModule.swatchesVariations();

								baselThemeModule.btnsToolTips();
								setTimeout(function () {
									baselThemeModule.nanoScroller();
								}, 300);
								baselThemeModule.quickViewCarousel();
							}
						},
					});
				}

				$.ajax({
					url: basel_settings.ajaxurl,
					data: data,
					method: 'get',
					success: function (data) {
						if (basel_settings.quickview_in_popup_fix) {
							$.magnificPopup.close();
							setTimeout(function () {
								initPopup(data);
							}, 500);
						} else {
							initPopup(data);
						}
					},
					complete: function () {
						btn.removeClass('loading');
					},
					error: function () {
					},
				});
			},


            /**
             *--------------------------------------------------------------------------
             * Quick Shop
             *--------------------------------------------------------------------------
             */

			quickShop: function () {

				var btnSelector = '.btn-quick-shop';

				$(document).on('click', btnSelector, function (e) {
					e.preventDefault();

					var $this = $(this),
						$product = $this.parents('.product'),
						$content = $product.find('.quick-shop-form'),
						id = $this.data('id'),
						loadingClass = 'btn-loading';

					if ($this.hasClass(loadingClass)) return;


					// Simply show quick shop form if it is already loaded with AJAX previously
					if ($product.hasClass('quick-shop-loaded')) {
						$product.addClass('quick-shop-shown');
						return;
					}

					$this.addClass(loadingClass);
					$product.addClass('loading-quick-shop');

					$.ajax({
						url: basel_settings.ajaxurl,
						data: {
							action: 'basel_quick_shop',
							id: id
						},
						method: 'get',
						success: function (data) {

							// insert variations form
							$content.append(data);

							initVariationForm($product);
							$('body').trigger('basel-quick-view-displayed');
							baselThemeModule.swatchesVariations();
							baselThemeModule.btnsToolTips();

						},
						complete: function () {
							$this.removeClass(loadingClass);
							$product.removeClass('loading-quick-shop');
							$product.addClass('quick-shop-shown quick-shop-loaded');
						},
						error: function () {
						},
					});

				})

					.on('click', '.quick-shop-close', function () {
						var $this = $(this),
							$product = $this.parents('.product');

						$product.removeClass('quick-shop-shown');

					});

				function initVariationForm($product) {
					$product.find('.variations_form').wc_variation_form().find('.variations select:eq(0)').change();
					$product.find('.variations_form').trigger('wc_variation_form');
				}
			},


            /**
             *--------------------------------------------------------------------------
             * ToolTips titles
             *--------------------------------------------------------------------------
             */
			btnsToolTips: function () {

				$('.basel-tooltip, .product-actions-btns > a, .product-grid-item .add_to_cart_button, .quick-view a, .product-compare-button a, .product-grid-item .yith-wcwl-add-to-wishlist a').each(function () {
					$(this).find('.basel-tooltip-label').remove();
					$(this).addClass('basel-tooltip').prepend('<span class="basel-tooltip-label">' + $(this).text() + '</span>');
				});

			},

            /**
             *--------------------------------------------------------------------------
             * Sticky footer: margin bottom for main wrapper
             *--------------------------------------------------------------------------
             */
			stickyFooter: function () {

				if (!$('body').hasClass('sticky-footer-on') || $(window).width() < 991) return;

				var $footer = $('.footer-container'),
					$page = $('.main-page-wrapper'),
					$window = $(window);

				if ($('.basel-prefooter').length > 0) {
					$page = $('.basel-prefooter');
				}

				var footerOffset = function () {
					$page.css({
						marginBottom: $footer.outerHeight()
					})
				};

				$window.on('resize', footerOffset);

				$footer.imagesLoaded(function () {
					footerOffset();
				});

				var footerScrollFix = function () {
					var windowScroll = $window.scrollTop();
					var footerOffsetTop = $(document).outerHeight() - $footer.outerHeight();

					if (footerOffsetTop < windowScroll + $footer.outerHeight() + $window.outerHeight()) {
						$footer.addClass('visible-footer');
					} else {
						$footer.removeClass('visible-footer');
					}
				};

				footerScrollFix();
				$window.on('scroll', footerScrollFix);

			},

            /**
             *--------------------------------------------------------------------------
             * Swatches variations
             *--------------------------------------------------------------------------
             */
			swatchesVariations: function () {

				var $variation_forms = $('.variations_form');
				var variationGalleryReplace = false;

				// Firefox mobile fix
				$('.variations_form .label').on('click', function (e) {
					if ($(this).siblings('.value').hasClass('with-swatches')) {
						e.preventDefault();
					}
				});

				$variation_forms.each(function () {
					var $variation_form = $(this);

					if ($variation_form.data('swatches')) return;
					$variation_form.data('swatches', true);

					// If AJX
					if (!$variation_form.data('product_variations')) {
						$variation_form.find('.swatches-select').find('> div').addClass('swatch-enabled');
					}

					if ($('.swatches-select > div').hasClass('active-swatch')) {
						$variation_form.addClass('variation-swatch-selected');
					}

					$variation_form.on('click', '.swatches-select > div', function () {
						var value = $(this).data('value');
						var id = $(this).parent().data('id');

						$variation_form.trigger('check_variations', ['attribute_' + id, true]);
						resetSwatches($variation_form);

						//$variation_form.find('select#' + id).val('').trigger('change');
						//$variation_form.trigger('check_variations');

						if ($(this).hasClass('active-swatch')) {
							// Removed since 2.9 version as not necessary
							// $variation_form.find( '.variations select' ).val( '' ).change();
							// $variation_form.trigger( 'reset_data' );
							// $(this).removeClass('active-swatch')
							return;
						}

						if ($(this).hasClass('swatch-disabled')) return;
						$variation_form.find('select#' + id).val(value).trigger('change');
						$(this).parent().find('.active-swatch').removeClass('active-swatch');
						$(this).addClass('active-swatch');
						resetSwatches($variation_form);
					})


						// On clicking the reset variation button
						.on('click', '.reset_variations', function (event) {
							$variation_form.find('.active-swatch').removeClass('active-swatch');
						})

						.on('reset_data', function () {
							var all_attributes_chosen = true;
							var some_attributes_chosen = false;

							$variation_form.find('.variations select').each(function () {
								var attribute_name = $(this).data('attribute_name') || $(this).attr('name');
								var value = $(this).val() || '';

								if (value.length === 0) {
									all_attributes_chosen = false;
								} else {
									some_attributes_chosen = true;
								}

							});

							if (all_attributes_chosen) {
								$(this).parent().find('.active-swatch').removeClass('active-swatch');
							}

							$variation_form.removeClass('variation-swatch-selected');

							var $mainOwl = $('.woocommerce-product-gallery__wrapper.owl-carousel');

							resetSwatches($variation_form);

							if ($mainOwl.length === 0) return;

							if (basel_settings.product_slider_auto_height == 'yes') {
								if (!isQuickView() && isVariationGallery('default') && variationGalleryReplace) {
									$mainOwl.trigger('destroy.owl.carousel');
								}
								$('.product-images').imagesLoaded(function () {
									$mainOwl = $mainOwl.owlCarousel(baselTheme.mainCarouselArg);
									$mainOwl.trigger('refresh.owl.carousel');
								});
							} else {
								$mainOwl = $mainOwl.owlCarousel(baselTheme.mainCarouselArg);
								$mainOwl.trigger('refresh.owl.carousel');
							}

							$mainOwl.trigger('to.owl.carousel', 0);

							replaceMainGallery('default', $variation_form);
						})


						// Update first tumbnail
						.on('reset_image', function () {
							var $thumb = $('.thumbnails img').first();
							if (!isQuickView() && !isQuickShop($variation_form)) {
								$thumb.wc_reset_variation_attr('src');
							}
						})
						.on('show_variation', function (e, variation, purchasable) {
							if (!variation.image.src) {
								return;
							}

							// See if the gallery has an image with the same original src as the image we want to switch to.
							var galleryHasImage = $variation_form.parents('.single-product-content').find('.thumbnails img[data-o_src="' + variation.image.thumb_src + '"]').length > 0;
							var $firstThumb = $variation_form.parents('.single-product-content').find('.thumbnails img').first();

							// If the gallery has the image, reset the images. We'll scroll to the correct one.
							if (galleryHasImage) {
								$firstThumb.wc_reset_variation_attr('src');
							}

							if (!isQuickShop($variation_form) && !replaceMainGallery(variation.variation_id, $variation_form)) {
								if ($firstThumb.attr('src') != variation.image.thumb_src) {
									$firstThumb.wc_set_variation_attr('src', variation.image.src);
								}
								baselThemeModule.initZoom();
							}

							var $mainOwl = $('.woocommerce-product-gallery__wrapper');

							$variation_form.addClass('variation-swatch-selected');

							if (!isQuickShop($variation_form) && !isQuickView()) {
								scrollToTop();
							}

							if (!$mainOwl.hasClass('owl-carousel')) return;

							if (basel_settings.product_slider_auto_height == 'yes') {
								if (!isQuickView() && isVariationGallery(variation.variation_id) && variationGalleryReplace) {
									$mainOwl.trigger('destroy.owl.carousel');
								}
								$('.product-images').imagesLoaded(function () {
									$mainOwl = $mainOwl.owlCarousel(baselTheme.mainCarouselArg);
									$mainOwl.trigger('refresh.owl.carousel');
								});
							} else {
								$mainOwl = $mainOwl.owlCarousel(baselTheme.mainCarouselArg);
								$mainOwl.trigger('refresh.owl.carousel');
							}

							var $thumbs = $('.images .thumbnails');

							$mainOwl.trigger('to.owl.carousel', 0);

							if ($thumbs.hasClass('owl-carousel')) {
								$thumbs.owlCarousel().trigger('to.owl.carousel', 0);
								$thumbs.find('.active-thumb').removeClass('active-thumb');
								$thumbs.find('.owl-item').eq(0).addClass('active-thumb');
							} else {
								$thumbs.slick('slickGoTo', 0);
								$thumbs.find('.active-thumb').removeClass('active-thumb');
								$thumbs.find('img').eq(0).addClass('active-thumb');
							}

						});

				})

				var resetSwatches = function ($variation_form) {

					// If using AJAX
					if (!$variation_form.data('product_variations')) return;

					$variation_form.find('.variations select').each(function () {

						var select = $(this);
						var swatch = select.parent().find('.swatches-select');
						var options = select.html();
						// var options = select.data('attribute_html');
						options = $(options);

						swatch.find('> div').removeClass('swatch-enabled').addClass('swatch-disabled');

						options.each(function (el) {
							var value = $(this).val();

							if ($(this).hasClass('enabled')) {
								// if( ! el.disabled ) {
								swatch.find('div[data-value="' + value + '"]').removeClass('swatch-disabled').addClass('swatch-enabled');
							} else {
								swatch.find('div[data-value="' + value + '"]').addClass('swatch-disabled').removeClass('swatch-enabled');
							}

						});

					});
				};

				var scrollToTop = function () {
					if ((basel_settings.swatches_scroll_top_desktop == 1 && $(window).width() >= 1024) || (basel_settings.swatches_scroll_top_mobile == 1 && $(window).width() <= 1024)) {
						var $page = $('html, body');

						$page.stop(true);
						$(window).on('mousedown wheel DOMMouseScroll mousewheel keyup touchmove', function () {
							$page.stop(true);
						});
						$page.animate({
							scrollTop: $('.product-image-summary').offset().top - 150
						}, 800);
					}
				};

				var isQuickShop = function ($form) {
					return $form.parent().hasClass('quick-shop-form');
				};

				var isQuickView = function () {
					return $('.single-product-content').hasClass('product-quick-view');
				};

				var isVariationGallery = function (key) {
					var variation_gallery_data = isQuickView() ? basel_qv_variation_gallery_data : basel_variation_gallery_data;

					return typeof variation_gallery_data !== 'undefined' && variation_gallery_data && variation_gallery_data[key];
				};

				var replaceMainGallery = function (key, $variationForm) {
					var variation_gallery_data = isQuickView() ? basel_qv_variation_gallery_data : basel_variation_gallery_data;

					if (!isVariationGallery(key) || isQuickShop($variationForm) || ('default' === key && !variationGalleryReplace)) {
						return false;
					}
					var imagesData = variation_gallery_data[key];
					var $mainGallery = $variationForm.parents('.single-product-content').find('.woocommerce-product-gallery__wrapper');
					$mainGallery.empty();

					for (var index = 0; index < imagesData.length; index++) {
						var $html = '<figure data-thumb="' + imagesData[index].data_thumb + '" class="woocommerce-product-gallery__image">';

						if (!isQuickView()) {
							$html += '<a href="' + imagesData[index].href + '">';
						}

						$html += imagesData[index].image;

						if (!isQuickView()) {
							$html += '</a>';
						}

						$html += '</figure>';

						$mainGallery.append($html);
					}

					baselThemeModule.productImagesGallery();
					baselThemeModule.quickViewCarousel();
					$('.woocommerce-product-gallery__image').trigger('zoom.destroy');
					if (!isQuickView()) {
						baselThemeModule.initZoom();
					}

					if ('default' === key) {
						variationGalleryReplace = false;
					} else {
						variationGalleryReplace = true;
					}

					return true;
				}

			},

			swatchesOnGrid: function () {

				$('body').on('click', '.swatch-on-grid', function () {

					var src, srcset, image_sizes;

					var imageSrc = $(this).data('image-src'),
						imageSrcset = $(this).data('image-srcset'),
						imageSizes = $(this).data('image-sizes');

					if (typeof imageSrc == 'undefined') return;

					var product = $(this).parents('.product-grid-item'),
						image = product.find('.product-element-top > a > img'),
						srcOrig = image.data('original-src'),
						srcsetOrig = image.data('original-srcset'),
						sizesOrig = image.data('original-sizes');

					if (typeof srcOrig == 'undefined') {
						image.data('original-src', image.attr('src'));
					}

					if (typeof srcsetOrig == 'undefined') {
						image.data('original-srcset', image.attr('srcset'));
					}

					if (typeof sizesOrig == 'undefined') {
						image.data('original-sizes', image.attr('sizes'));
					}


					if ($(this).hasClass('current-swatch')) {
						src = srcOrig;
						srcset = srcsetOrig;
						image_sizes = sizesOrig;
						$(this).removeClass('current-swatch');
						product.removeClass('product-swatched');
					} else {
						$(this).parent().find('.current-swatch').removeClass('current-swatch');
						$(this).addClass('current-swatch');
						product.addClass('product-swatched');
						src = imageSrc;
						srcset = imageSrcset;
						image_sizes = imageSizes;
					}

					if (image.attr('src') == src) return;

					product.addClass('loading-image');

					image.attr('src', src).attr('srcset', srcset).attr('image_sizes', image_sizes).one('load', function () {
						product.removeClass('loading-image');
					});

				});

			},


            /**
             *--------------------------------------------------------------------------
             * Ajax filters
             *--------------------------------------------------------------------------
             */
			ajaxFilters: function () {

				if (!$('body').hasClass('basel-ajax-shop-on')) return;

				var that = this,
					filtersState = false,
					products = $('.products');

				$('body').on('click', '.post-type-archive-product .products-footer .woocommerce-pagination a', function (e) {
					scrollToTop();
				});

				$(document).pjax(baselTheme.ajaxLinks, '.main-page-wrapper', {
					timeout: basel_settings.pjax_timeout,
					scrollTo: false
				});


				$(document).on('click', '.widget_price_filter form .button', function () {
					var form = $('.widget_price_filter form');
					console.log(form.serialize());
					$.pjax({
						container: '.main-page-wrapper',
						timeout: basel_settings.pjax_timeout,
						url: form.attr('action'),
						data: form.serialize(),
						scrollTo: false
					});

					return false;
				});


				$(document).on('pjax:error', function (xhr, textStatus, error, options) {
					console.log('pjax error ' + error);
				});

				$(document).on('pjax:start', function (xhr, options) {
					$('body').addClass('basel-loading');
					baselThemeModule.hideShopSidebar();
				});

				$(document).on('pjax:beforeReplace', function (contents, options) {
					if ($('.filters-area').hasClass('filters-opened') && basel_settings.shop_filters_close == 'yes') {
						filtersState = true;
						$('body').addClass('body-filters-opened');
					}
				});

				$(document).on('pjax:complete', function (xhr, textStatus, options) {

					that.shopPageInit();

					scrollToTop();

					$(document).trigger('basel-images-loaded');

					$('.baselmart-sidebar-content').scroll(function () {
						$(document).trigger('basel-images-loaded');
					})

					$('body').removeClass('basel-loading');

				});

				$(document).on('pjax:end', function (xhr, textStatus, options) {

					if (filtersState) {
						$('.filters-area').css('display', 'block');
						baselThemeModule.openFilters(200);
						filtersState = false;
					}

					$('body').removeClass('basel-loading');

				});

				var scrollToTop = function () {
					if (basel_settings.ajax_scroll == 'no') return false;

					var $scrollTo = $(basel_settings.ajax_scroll_class),
						scrollTo = $scrollTo.offset().top - basel_settings.ajax_scroll_offset;

					$('html, body').stop().animate({
						scrollTop: scrollTo
					}, 400);
				};

			},

            /**
             *--------------------------------------------------------------------------
             * init shop page JS functions
             *--------------------------------------------------------------------------
             */
			shopPageInit: function () {
				this.shopMasonry();
				//this.filtersArea();
				this.ajaxSearch();
				this.btnsToolTips();
				this.compare();
				this.filterDropdowns();
				this.categoriesMenuBtns();
				this.sortByWidget();
				this.categoriesAccordion();
				this.woocommercePriceSlider();
				this.updateCartWidgetFromLocalStorage(); // refresh cart in sidebar
				this.nanoScroller();
				this.countDownTimer();
				this.shopLoader();
				this.stickySidebarBtn();
				this.productFilters();

				baselThemeModule.clickOnScrollButton(baselTheme.shopLoadMoreBtn, false);

				$('.woocommerce-ordering').on('change', 'select.orderby', function () {
					$(this).closest('form').find('[name="_pjax"]').remove();
					$(this).closest('form').submit();
				});

				$(document.body).on('updated_wc_div', function () {
					$(document).trigger('basel-images-loaded');
				});

				$(document).trigger('resize.vcRowBehaviour');
			},

            /**
             *--------------------------------------------------------------------------
             * Add filters dropdowns compatibility
             *--------------------------------------------------------------------------
             */
			filterDropdowns: function () {
				// Init
				$('.basel-widget-layered-nav-dropdown-form').each(function () {
					var $form = $(this);
					var $select = $form.find('select');
					var slug = $select.data('slug');

					$select.change(function () {
						var val = $(this).val();
						$('input[name=filter_' + slug + ']').val(val);
					});

					if ($().selectWoo) {
						$select.selectWoo({
							placeholder: $select.data('placeholder'),
							minimumResultsForSearch: 5,
							width: '100%',
							allowClear: $select.attr('multiple') ? false : true,
							language: {
								noResults: function () {
									return $select.data('noResults');
								}
							}
						}).on('select2:unselecting', function () {
							$(this).data('unselecting', true);
						}).on('select2:opening', function (e) {
							if ($(this).data('unselecting')) {
								$(this).removeData('unselecting');
								e.preventDefault();
							}
						});
					}
				});

				function ajaxAction($element) {
					var $form = $element.parent('.basel-widget-layered-nav-dropdown-form');
					if (typeof ($.fn.pjax) == 'undefined') {
						return;
					}

					$.pjax({
						container: '.main-page-wrapper',
						timeout: basel_settings.pjax_timeout,
						url: $form.attr('action'),
						data: $form.serialize(),
						scrollTo: false
					});
				}

				$('.basel-widget-layered-nav-dropdown__submit').on('click', function (e) {
					if (!$(this).siblings('select').attr('multiple') || !$('body').hasClass('basel-ajax-shop-on')) {
						return;
					}

					ajaxAction($(this));

					$(this).prop('disabled', true);
				});

				$('.basel-widget-layered-nav-dropdown-form select').on('change', function (e) {
					if (!$('body').hasClass('basel-ajax-shop-on')) {
						$(this).parent().submit();
						return;
					}

					ajaxAction($(this));
				});
			},


            /**
             *--------------------------------------------------------------------------
             * Back in history
             *--------------------------------------------------------------------------
             */
			backHistory: function () {
				history.go(-1);

				setTimeout(function () {
					$('.filters-area').removeClass('filters-opened').stop().hide();
					$('.open-filters').removeClass('btn-opened');
					if ($(window).width() < 992) {
						$('.basel-product-categories').removeClass('categories-opened').stop().hide();
						$('.basel-show-categories').removeClass('button-open');
					}

					baselThemeModule.woocommercePriceSlider();
				}, 20);


			},

            /**
             *--------------------------------------------------------------------------
             * Categories menu for mobile
             *--------------------------------------------------------------------------
             */
			categoriesMenu: function () {
				if ($(window).width() > 991) return;

				var categories = $('.basel-product-categories'),
					subCategories = categories.find('li > ul'),
					button = $('.basel-show-categories'),
					time = 200;


				//this.categoriesMenuBtns();

				$('body').on('click', '.icon-drop-category', function () {
					if ($(this).parent().find('> ul').hasClass('child-open')) {
						$(this).removeClass("basel-act-icon").parent().find('> ul').slideUp(time).removeClass('child-open');
					} else {
						$(this).addClass("basel-act-icon").parent().find('> ul').slideDown(time).addClass('child-open');
					}
				});

				$('body').on('click', '.basel-show-categories', function (e) {
					e.preventDefault();

					console.log('close click');

					if (isOpened()) {
						closeCats();
					} else {
						//setTimeout(function() {
						openCats();
						//}, 50);
					}
				});

				$('body').on('click', '.basel-product-categories a', function (e) {
					closeCats();
					categories.stop().attr('style', '');
				});

				var isOpened = function () {
					return $('.basel-product-categories').hasClass('categories-opened');
				};

				var openCats = function () {
					$('.basel-product-categories').addClass('categories-opened').stop().slideDown(time);
					$('.basel-show-categories').addClass('button-open');

				};

				var closeCats = function () {
					$('.basel-product-categories').removeClass('categories-opened').stop().slideUp(time);
					$('.basel-show-categories').removeClass('button-open');
				};
			},


			categoriesMenuBtns: function () {
				if ($(window).width() > 991) return;

				var categories = $('.basel-product-categories'),
					subCategories = categories.find('li > ul'),
					iconDropdown = '<span class="icon-drop-category"></span>';

				categories.addClass('responsive-cateogires');
				subCategories.parent().addClass('has-sub').prepend(iconDropdown);
			},


            /**
             *--------------------------------------------------------------------------
             * Categories toggle accordion
             *--------------------------------------------------------------------------
             */

			categoriesAccordion: function () {
				if (basel_settings.categories_toggle == 'no') return;

				var $widget = $('.widget_product_categories'),
					$list = $widget.find('.product-categories'),
					$openBtn = $('<div class="basel-cats-toggle" />'),
					time = 300;

				$list.find('.cat-parent').append($openBtn);

				$list.on('click', '.basel-cats-toggle', function () {
					var $btn = $(this),
						$subList = $btn.prev();

					if ($subList.hasClass('list-shown')) {
						$btn.removeClass('toggle-active');
						$subList.stop().slideUp(time).removeClass('list-shown');
					} else {
						$subList.parent().parent().find('> li > .list-shown').slideUp().removeClass('list-shown');
						$subList.parent().parent().find('> li > .toggle-active').removeClass('toggle-active');
						$btn.addClass('toggle-active');
						$subList.stop().slideDown(time).addClass('list-shown');
					}
				});

				if ($list.find('li.current-cat.cat-parent, li.current-cat-parent').length > 0) {
					$list.find('li.current-cat.cat-parent, li.current-cat-parent').find('> .basel-cats-toggle').click();
				}

			},


            /**
             *--------------------------------------------------------------------------
             * WooCommerce price filter slider with ajax
             *--------------------------------------------------------------------------
             */

			woocommercePriceSlider: function () {

				// woocommerce_price_slider_params is required to continue, ensure the object exists
				if (typeof woocommerce_price_slider_params === 'undefined' || $('.price_slider_amount #min_price').length < 1 || !$.fn.slider) {
					return false;
				}

				var $slider = $('.price_slider');
				if ($slider.slider('instance') !== undefined) return;

				// Get markup ready for slider
				$('input#min_price, input#max_price').hide();
				$('.price_slider, .price_label').show();

				// Price slider uses jquery ui
				var min_price = $('.price_slider_amount #min_price').data('min'),
					max_price = $('.price_slider_amount #max_price').data('max'),
					current_min_price = parseInt(min_price, 10),
					current_max_price = parseInt(max_price, 10);

				if ($('.products').attr('data-min_price') && $('.products').attr('data-min_price').length > 0) {
					current_min_price = parseInt($('.products').attr('data-min_price'), 10);
				}
				if ($('.products').attr('data-max_price') && $('.products').attr('data-max_price').length > 0) {
					current_max_price = parseInt($('.products').attr('data-max_price'), 10);
				}

				$slider.slider({
					range: true,
					animate: true,
					min: min_price,
					max: max_price,
					values: [current_min_price, current_max_price],
					create: function () {

						$('.price_slider_amount #min_price').val(current_min_price);
						$('.price_slider_amount #max_price').val(current_max_price);

						$(document.body).trigger('price_slider_create', [current_min_price, current_max_price]);
					},
					slide: function (event, ui) {

						$('input#min_price').val(ui.values[0]);
						$('input#max_price').val(ui.values[1]);

						$(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
					},
					change: function (event, ui) {

						$(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
					}
				});

				setTimeout(function () {
					$(document.body).trigger('price_slider_create', [current_min_price, current_max_price]);
					if ($slider.find('.ui-slider-range').length > 1) $slider.find('.ui-slider-range').first().remove();
				}, 10);
			},


            /**
             *--------------------------------------------------------------------------
             * Filters area
             *--------------------------------------------------------------------------
             */
			filtersArea: function () {
				var filters = $('.filters-area'),
					btn = $('.open-filters'),
					time = 200;

				$('body').on('click', '.open-filters', function (e) {
					e.preventDefault();

					if (isOpened()) {
						closeFilters();
					} else {
						baselThemeModule.openFilters();
						setTimeout(function () {
							baselThemeModule.shopLoader();
						}, time);
					}

				});

				if (basel_settings.shop_filters_close == 'no') {
					$('body').on('click', baselTheme.ajaxLinks, function () {
						if (isOpened()) {
							closeFilters();
						}
					});

				}

				var isOpened = function () {
					filters = $('.filters-area')
					return filters.hasClass('filters-opened');
				};

				var closeFilters = function () {
					filters = $('.filters-area')
					filters.removeClass('filters-opened');
					filters.stop().slideUp(time);
					$('.open-filters').removeClass('btn-opened');
				};
			},

			openFilters: function (time) {
				var filters = $('.filters-area')
				filters.addClass('filters-opened');
				filters.stop().slideDown(time);
				$('.open-filters').addClass('btn-opened');
				setTimeout(function () {
					filters.addClass('filters-opened');
					$('body').removeClass('body-filters-opened');
					baselThemeModule.nanoScroller();
					$(document).trigger('basel-images-loaded');
				}, time);
			},

            /**
             *--------------------------------------------------------------------------
             * Ajax Search for products
             *--------------------------------------------------------------------------
             */
			ajaxSearch: function () {

				var escapeRegExChars = function (value) {
					return value.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
				};

				$('form.basel-ajax-search').each(function () {
					var $this = $(this),
						number = parseInt($this.data('count')),
						thumbnail = parseInt($this.data('thumbnail')),
						productCat = $this.find('[name="product_cat"]'),
						$results = $this.parent().find('.basel-search-results'),
						price = parseInt($this.data('price')),
						url = basel_settings.ajaxurl + '?action=basel_ajax_search',
						postType = $this.data('post_type');

					if (number > 0) url += '&number=' + number;

					url += '&post_type=' + postType;

					$results.on('click', '.view-all-result', function () {
						$this.submit();
					});

					if (productCat.length && productCat.val() !== '') {
						url += '&product_cat=' + productCat.val();
					}

					$this.find('[type="text"]').devbridgeAutocomplete({
						serviceUrl: url,
						appendTo: $results,
						onSelect: function (suggestion) {
							if (suggestion.permalink.length > 0)
								window.location.href = suggestion.permalink;
						},
						onSearchStart: function (query) {
							$this.addClass('search-loading');
						},
						beforeRender: function (container) {

							if (container[0].childElementCount > 2)
								$(container).append('<div class="view-all-result"><span>' + basel_settings.all_results + '</span></div>');

						},
						onSearchComplete: function (query, suggestions) {
							$this.removeClass('search-loading');
							$(".basel-scroll").nanoScroller({
								paneClass: 'basel-scroll-pane',
								sliderClass: 'basel-scroll-slider',
								contentClass: 'basel-scroll-content',
								preventPageScrolling: true
							});

							$(document).trigger('basel-images-loaded');
						},
						formatResult: function (suggestion, currentValue) {
							if (currentValue == '&') currentValue = "&#038;";
							var pattern = '(' + escapeRegExChars(currentValue) + ')',
								returnValue = '';

							if (thumbnail && suggestion.thumbnail) {
								returnValue += ' <div class="suggestion-thumb">' + suggestion.thumbnail + '</div>';
							}

							returnValue += '<h4 class="suggestion-title result-title">' + suggestion.value
								.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>')
								// .replace(/&/g, '&amp;')
								.replace(/</g, '&lt;')
								.replace(/>/g, '&gt;')
								.replace(/"/g, '&quot;')
								.replace(/&lt;(\/?strong)&gt;/g, '<$1>') + '</h4>';

							if (price && suggestion.price) {
								returnValue += ' <div class="suggestion-price price">' + suggestion.price + '</div>';
							}

							return returnValue;
						}
					});

					if (productCat.length) {

						var searchForm = $this.find('[type="text"]').devbridgeAutocomplete(),
							serviceUrl = basel_settings.ajaxurl + '?action=basel_ajax_search';

						if (number > 0) serviceUrl += '&number=' + number;
						serviceUrl += '&post_type=' + postType;

						productCat.on('cat_selected', function () {
							if (productCat.val() != '') {
								searchForm.setOptions({
									serviceUrl: serviceUrl + '&product_cat=' + productCat.val()
								});
							} else {
								searchForm.setOptions({
									serviceUrl: serviceUrl
								});
							}

							searchForm.hide();
							searchForm.onValueChange();
						});
					}

				});

			},


            /**
             *--------------------------------------------------------------------------
             * Search full screen
             *--------------------------------------------------------------------------
             */
			searchFullScreen: function () {

				var body = $('body'),
					searchWrapper = $('.basel-search-wrapper'),
					offset = 0;

				body.on('click', '.search-button > a', function (e) {
					e.preventDefault();

					if (!searchWrapper.find('.searchform').hasClass('basel-ajax-search') && $('.search-button').hasClass('basel-search-dropdown') || $(window).width() < 1024) return;

					if ($('.sticky-header.act-scroll').length > 0) {
						searchWrapper = $('.sticky-header .basel-search-wrapper');
					} else {
						searchWrapper = $('.main-header .basel-search-wrapper');
					}
					if (isOpened()) {
						closeWidget();
					} else {
						setTimeout(function () {
							openWidget();
						}, 10);
					}
				})


				body.on("click", ".basel-close-search, .main-header, .sticky-header, .topbar-wrapp, .main-page-wrapper, .header-banner", function (event) {

					if (!$(event.target).is('.basel-close-search') && $(event.target).closest(".basel-search-wrapper").length) return;

					if (isOpened()) {
						closeWidget();
					}
				});

				var closeWidget = function () {
					$('body').removeClass('basel-search-opened');
					searchWrapper.removeClass('search-overlap');
				};

				var openWidget = function () {
					var bar = $('#wpadminbar').outerHeight();

					var banner = $('.header-banner').outerHeight();

					var offset = $('.main-header').outerHeight() + bar;

					if (!$('.main-header').hasClass('act-scroll')) {
						offset += $('.topbar-wrapp').outerHeight();
						if ($('body').hasClass('header-banner-display')) {
							offset += banner;
						}
					}

					if ($('.sticky-header').hasClass('header-clone') && $('.sticky-header').hasClass('act-scroll')) {
						offset = $('.sticky-header').outerHeight() + bar;
					}

					if ($('.main-header').hasClass('header-menu-top') && $('.header-spacing')) {
						offset = $('.header-spacing').outerHeight() + bar;
						if ($('body').hasClass('header-banner-display')) {
							offset += banner;
						}
					}

					if ($('.header-menu-top').hasClass('act-scroll')) {
						offset = $('.header-menu-top.act-scroll .navigation-wrap').outerHeight() + bar;
					}

					searchWrapper.css('top', offset);

					$('body').addClass('basel-search-opened');
					searchWrapper.addClass('search-overlap');
					setTimeout(function () {
						searchWrapper.find('input[type="text"]').focus();
						$(window).one('scroll', function () {
							if (isOpened()) {
								closeWidget();
							}
						});
					}, 300);
				};

				var isOpened = function () {
					return $('body').hasClass('basel-search-opened');
				};
			},


            /**
             *--------------------------------------------------------------------------
             * Login tabs for my account page
             *--------------------------------------------------------------------------
             */
			loginTabs: function () {
				var tabs = $('.basel-register-tabs'),
					btn = tabs.find('.basel-switch-to-register'),
					login = tabs.find('.col-login'),
					title = $('.col-register-text h2'),
					loginText = tabs.find('.login-info'),
					register = tabs.find('.col-register'),
					classOpened = 'active-register',
					loginLabel = btn.data('login'),
					registerLabel = btn.data('register'),
					loginTitleLabel = btn.data('login-title'),
					registerTitleLabel = btn.data('reg-title');

				btn.on('click', function (e) {
					e.preventDefault();

					if (isShown()) {
						hideRegister();
					} else {
						showRegister();
					}

					var scrollTo = $('.main-page-wrapper').offset().top - 100;

					if ($(window).width() < 768) {
						$('html, body').stop().animate({
							scrollTop: tabs.offset().top - 50
						}, 400);
					}
				});

				var showRegister = function () {
					tabs.addClass(classOpened);
					btn.text(loginLabel);
					if (loginText.length > 0) {
						title.text(loginTitleLabel);
					}
				};

				var hideRegister = function () {
					tabs.removeClass(classOpened);
					btn.text(registerLabel);
					if (loginText.length > 0) {
						title.text(registerTitleLabel);
					}
				};

				var isShown = function () {
					return tabs.hasClass(classOpened);
				};
			},


            /**
             *--------------------------------------------------------------------------
             * Product accordion
             *--------------------------------------------------------------------------
             */
			productAccordion: function () {
				var $accordion = $('.tabs-layout-accordion');

				var time = 300;

				var hash = window.location.hash;
				var url = window.location.href;

				if (hash.toLowerCase().indexOf('comment-') >= 0 || hash === '#reviews' || hash === '#tab-reviews') {
					$accordion.find('.tab-title-reviews').addClass('active');
				} else if (url.indexOf('comment-page-') > 0 || url.indexOf('cpage=') > 0) {
					$accordion.find('.tab-title-reviews').addClass('active');
				} else {
					$accordion.find('.basel-accordion-title').first().addClass('active');
				}

				$accordion.on('click', '.basel-accordion-title', function (e) {
					e.preventDefault();

					var $this = $(this),
						$panel = $this.siblings('.woocommerce-Tabs-panel');

					if ($this.hasClass('active')) {
						$this.removeClass('active');
						$panel.stop().slideUp(time);
					} else {
						$accordion.find('.basel-accordion-title').removeClass('active');
						$accordion.find('.woocommerce-Tabs-panel').slideUp();
						$this.addClass('active');
						$panel.stop().slideDown(time);
					}

					$(window).resize();

					setTimeout(function () {
						$(window).resize();
					}, time);

				});
			},


            /**
             *--------------------------------------------------------------------------
             * Compact product layout
             *--------------------------------------------------------------------------
             */
			productCompact: function () {
				$(".product-design-compact .basel-scroll").nanoScroller({
					paneClass: 'basel-scroll-pane',
					sliderClass: 'basel-scroll-slider',
					contentClass: 'basel-scroll-content',
					preventPageScrolling: false
				});
			},


            /**
             *--------------------------------------------------------------------------
             * Sale final date countdown
             *--------------------------------------------------------------------------
             */
			countDownTimer: function () {

				$('.basel-timer').each(function () {
					var time = moment.tz($(this).data('end-date'), $(this).data('timezone'));
					$(this).countdown(time.toDate(), function (event) {
						$(this).html(event.strftime(''
							+ '<span class="countdown-days">%-D <span>' + basel_settings.countdown_days + '</span></span> '
							+ '<span class="countdown-hours">%H <span>' + basel_settings.countdown_hours + '</span></span> '
							+ '<span class="countdown-min">%M <span>' + basel_settings.countdown_mins + '</span></span> '
							+ '<span class="countdown-sec">%S <span>' + basel_settings.countdown_sec + '</span></span>'));
					});
				});

			},


            /**
             *--------------------------------------------------------------------------
             * Remove click delay on mobile
             *--------------------------------------------------------------------------
             */
			mobileFastclick: function () {

				if ('addEventListener' in document) {
					document.addEventListener('DOMContentLoaded', function () {
						FastClick.attach(document.body);
					}, false);
				}

			},

            /**
             *--------------------------------------------------------------------------
             * Init nanoscroller
             *--------------------------------------------------------------------------
             */
			nanoScroller: function () {

				$(".basel-scroll").nanoScroller({
					paneClass: 'basel-scroll-pane',
					sliderClass: 'basel-scroll-slider',
					contentClass: 'basel-scroll-content',
					preventPageScrolling: false
				});

			},

            /**
             *--------------------------------------------------------------------------
             * Fix comments
             *--------------------------------------------------------------------------
             */
			woocommerceComments: function () {
				var hash = window.location.hash;
				var url = window.location.href;

				if (hash.toLowerCase().indexOf('comment-') >= 0 || hash === '#reviews' || hash === '#tab-reviews' || url.indexOf('comment-page-') > 0 || url.indexOf('cpage=') > 0) {

					setTimeout(function () {
						window.scrollTo(0, 0);
					}, 1);

					setTimeout(function () {
						if ($(hash).length > 0) {
							$('html, body').stop().animate({
								scrollTop: $(hash).offset().top - 100
							}, 400);
						}
					}, 10);

				}
			},

            /**
             *--------------------------------------------------------------------------
             * Quantityt +/-
             *--------------------------------------------------------------------------
             */
			woocommerceQuantity: function () {
				if (!String.prototype.getDecimals) {
					String.prototype.getDecimals = function () {
						var num = this,
							match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
						if (!match) {
							return 0;
						}
						return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
					}
				}


				$(document).on('click', '.plus, .minus', function () {
					// Get values
					var $qty = $(this).closest('.quantity').find('.qty'),
						currentVal = parseFloat($qty.val()),
						max = parseFloat($qty.attr('max')),
						min = parseFloat($qty.attr('min')),
						step = $qty.attr('step');

					// Format values
					if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
					if (max === '' || max === 'NaN') max = '';
					if (min === '' || min === 'NaN') min = 0;
					if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = '1';

					// Change the value
					if ($(this).is('.plus')) {
						if (max && (currentVal >= max)) {
							$qty.val(max);
						} else {
							$qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
						}
					} else {
						if (min && (currentVal <= min)) {
							$qty.val(min);
						} else if (currentVal > 0) {
							$qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
						}
					}

					// Trigger change event
					$qty.trigger('change');
				});

			},
		}
	}());

})(jQuery);


jQuery(document).ready(function () {

	baselThemeModule.init();

});
