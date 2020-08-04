<?php
defined('ABSPATH') or die('No script kiddies please!');
/**
 * Plugin Name: PE Easy Slider
 * Plugin URI: http://pixelemu.com
 * Description: Simple Horizontal Slider for Posts
 * Version: 1.1.0
 * Author: pixelemu.com
 * Author URI: http://www.pixelemu.com
 * Text Domain: pe-easy-slider
 * License: GPLv2 or later
 */


if (!class_exists('PE_Recent_Posts_Horizontal')) {
	class PE_Recent_Posts_Horizontal extends WP_Widget
	{

		public function __construct()
		{
			$widget_ops = array(
				'classname' => 'PE_Recent_Posts_Horizontal',
				'description' => __('Easy Posts Slider.', 'pe-easy-slider'),
			);
			parent::__construct('PE_Recent_Posts_Horizontal', 'PE Easy Slider', $widget_ops);
		}

		function widget($args,  $setup)
		{
			extract($args);
			$source = $setup['source'];
			$source_folder_images_directory = $setup['source_folder_images_directory'];
			$unique_id = $this->id;
			if ($setup['number_of_items_in_row'] == '') {
				$number_of_items_in_row = 1;
			} else {
				$number_of_items_in_row = $setup['number_of_items_in_row'];
			}
			if ($setup['number_of_rows'] == '') {
				$number_of_rows = 1;
			} else {
				$number_of_rows = $setup['number_of_rows'];
			}
			$navigation_way = $setup['navigation_way'];
			if ($source == 'posts') {
				$sticky_posts = $setup['sticky_posts'];
				$styles = $setup['styles'];
				$count_posts = wp_count_posts('post');
				$readmore = $setup['readmore'];
				$number_of_all_items = $setup['number_of_all_items'];
				if ($number_of_all_items > $count_posts->publish) {
					$number_of_all_items = $count_posts->publish;
				}
				$order_posts = $setup['order_posts'];
				$order_direction = $setup['order_direction'];
				$array_loop_values = array(
					'relation' => 'AND',
					array(
						'key' => '_thumbnail_id',
						'compare' => 'EXISTS'
					)
				);

				$loop_check = new WP_Query(array(
					'post_type' => 'post',
					'posts_per_page' => '' . $number_of_all_items . '',
					'ignore_sticky_posts' => '' . $sticky_posts . '',
					'orderby' => '' . $order_posts . '',
					'order' => '' . $order_direction . '',
					'meta_query' => $array_loop_values
				));
				$counter_check = 0;
				while ($loop_check->have_posts()) : $loop_check->the_post();
					$counter_check++;
				endwhile;
				$bullets_on_board = '';
				if (($navigation_way == 1) && ($counter_check > ($number_of_items_in_row * $number_of_rows))) {
					$bullets_on_board = 'bullets-on-board';
				}
				wp_reset_query();
				if ($number_of_all_items > $counter_check) {
					$number_of_all_items = $counter_check;
				}

				$category_id = $setup['category_id'];
				$loop = new WP_Query(array(
					'post_type' => 'post',
					'posts_per_page' => '' . $number_of_all_items . '',
					'ignore_sticky_posts' => '' . $sticky_posts . '',
					'orderby' => '' . $order_posts . '',
					'order' => '' . $order_direction . '',
					'meta_query' => $array_loop_values,
					'cat' => $category_id
				));
			}


			$title_widget = apply_filters('widget_title', $setup['title']);
			if (empty($title_widget)) {
				$title_widget = false;
				$before_title = false;
				$after_title = false;
			}
			echo $before_widget;
			echo $before_title;
			echo $title_widget;
			echo $after_title;

			$interval = '0';
			if (!empty($setup['interval'])) {
				$interval = $setup['interval'];
			}

			$slider_pause = 'null';
			if (!empty($setup['slider_pause'])) {
				$slider_pause = $setup['slider_pause'];
			}
			$image_height = $setup['image_height'];
			$grid_spacing = $setup['grid_spacing'];
			$slide_width = 100 / $number_of_items_in_row;
			$image_size = $setup['image_size'];
			if ($image_height == 0) {
				$height_slide = 'wp-size';
			} else {
				$height_slide = 'custom-size';
			}
			$even_odd = '';
			if ($number_of_items_in_row % 2) {
				$even_odd = 'odd-items-in-row';
			} else {
				$even_odd = 'even-items-in-row';
			}
?>
			<?php if ($source == 'posts') { ?>
				<?php if ($loop->have_posts()) { ?>
					<div id="myCarouselSlider<?php echo $unique_id; ?>" class="slider-carousel-outer carousel slide <?php echo $bullets_on_board . ' style' . $styles . ' ' . $even_odd; ?>">
						<div class="carousel-inner <?php echo $height_slide; ?>" style="margin-bottom: -<?php echo $grid_spacing; ?>px;">
							<?php
							if ($image_height == 0) {
								$image_height = 'auto';
							} else {
								$image_height = $image_height . 'px';
							}
							$counter = 0;
							while ($loop->have_posts()) : $loop->the_post(); ?>
								<?php
								$post_url = get_permalink();
								$post_title = get_the_title();
								$counter++;
								if (($number_of_items_in_row * $number_of_rows) == 1) {
									if ($counter == 1) { ?>
										<div class="item active" style="margin-left: -<?php echo $grid_spacing; ?>px;">
										<?php } else { ?>
											<div class="item" style="margin-left: -<?php echo $grid_spacing; ?>px;">
											<?php } ?>

											<?php } else {
											if (($counter % ($number_of_items_in_row * $number_of_rows) == 1)) {
												if ($counter == 1) { ?>
													<div class="item active" style="margin-left: -<?php echo $grid_spacing; ?>px;">
													<?php } else { ?>
														<div class="item" style="margin-left: -<?php echo $grid_spacing; ?>px;">
														<?php } ?>
												<?php }
										}
										$final_link = $post_url; ?>
												<ul class="thumbnails" style="width: <?php echo $slide_width; ?>%;">
													<li>
														<div class="thumbnail" style="padding-left: <?php echo $grid_spacing; ?>px; padding-bottom: <?php echo $grid_spacing; ?>px;">
															<div class="thumbnail-in">
																<?php
																$image_id = get_post_thumbnail_id();
																$image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
																if (!empty($image_alt)) {
																	$image_alternative_text = $image_alt;
																} else {
																	$image_alternative_text = $post_title;
																}
																echo the_post_thumbnail($image_size, array(
																	'alt'   => $image_alternative_text,
																	'style' => 'height: ' . $image_height
																));
																echo '<div class="pe-easy-slider-title-readmore"><div class="pe-easy-slider-title-readmore-in"><div class="pe-easy-slider-title-readmore-in-in">';
																echo '<a class="post-title fadeInUp animated" href="' . $final_link . '"><span class="post-title-in">' . $post_title . '</span></a>';
																if ($readmore == 1) {
																	echo '<a class="pe-easy-slider-readmore fadeInUp animated" href="' . $final_link . '">' . __('Read more...', 'pe-easy-slider') . '</a>';
																}
																echo '</div></div></div>';
																?>
															</div>
														</div>
													</li>
												</ul>
												<?php if (!($counter % 2)) {
													echo '<div class="pe-slides-separator-even">&nbsp;</div>';
												} ?>
												<?php if (!($counter % $number_of_items_in_row)) {
													echo '<div class="pe-slides-separator">&nbsp;</div>';
												} ?>
												<?php if (($counter % ($number_of_items_in_row * $number_of_rows)) == 0) { ?>
														</div>
													<?php } ?>
												<?php endwhile; ?>
												<?php if ((($counter % ($number_of_items_in_row * $number_of_rows)) != 0) && ($counter >= ($number_of_items_in_row * $number_of_rows))) { ?>
													</div>
												<?php } ?>
												<?php wp_reset_query(); ?>
											</div>
											<?php
											if ($counter < ($number_of_items_in_row * $number_of_rows)) { ?>
										</div>
									<?php } ?>
									<?php $counter2 = 0; ?>
									<?php if (($navigation_way == 1) && ($counter > ($number_of_items_in_row * $number_of_rows))) { ?>
										<ol class="carousel-indicators">
											<?php while ($loop->have_posts()) : $loop->the_post(); ?>
												<?php $counter2++; ?>
												<?php if (($counter2 % ($number_of_items_in_row * $number_of_rows) == 1) || ($number_of_items_in_row * $number_of_rows) == 1) {
													if ($counter2 == 1) { ?>
														<li data-target="#myCarouselSlider<?php echo $unique_id; ?>" data-slide-to="0" class="active" tabindex="0">item-0</li>
													<?php } else { ?>
														<li data-target="#myCarouselSlider<?php echo $unique_id; ?>" data-slide-to="<?php echo ($counter2 - 1) / ($number_of_items_in_row * $number_of_rows); ?>" tabindex="0">item-<?php echo ($counter2 - 1) / ($number_of_items_in_row * $number_of_rows); ?></li>
													<?php } ?>
												<?php } ?>
											<?php endwhile; ?>
											<?php wp_reset_query(); ?>
										</ol>
									<?php } else if (($navigation_way == 2) && ($counter > ($number_of_items_in_row * $number_of_rows))) { ?>
										<a class="carousel-control left" href="#myCarouselSlider<?php echo $unique_id; ?>" data-slide="prev" style="margin-top: <?php echo -16 - ($grid_spacing / 2); ?>px;"><span class="sr-only"><?php _e('Previous', 'pe-easy-slider'); ?></span></a>
										<a class="carousel-control right" href="#myCarouselSlider<?php echo $unique_id; ?>" data-slide="next" style="margin-top: <?php echo -16 - ($grid_spacing / 2); ?>px;"><span class="sr-only"><?php _e('Next', 'pe-easy-slider'); ?></span></a>
										<button class="playButton btn btn-default btn-xs" type="button" style="margin-top: <?php echo -7 - ($grid_spacing / 2); ?>px;"><span class="sr-only"><?php _e('Play', 'pe-easy-slider'); ?></span></button>
										<button class="pauseButton btn btn-default btn-xs" type="button" style="margin-top: <?php echo -7 - ($grid_spacing / 2); ?>px;"><span class="sr-only"><?php _e('Pause', 'pe-easy-slider'); ?></span></button>
									<?php } ?>
						</div>
					<?php } ?>
				<?php } else if ($source == 'folder') { ?>
					<?php
					$uploads = wp_upload_dir();
					$destination_directory = $uploads['basedir'] . '/' . $source_folder_images_directory;
					if ((file_exists($destination_directory)) && (count(glob("$destination_directory/*")) != 0)) { ?>
						<?php $dir = opendir($destination_directory); ?>
						<?php
						$counter = 0;
						$counter_check = 0;
						if ($dir) {
							$images = array();
							while (false !== ($file = readdir($dir))) {
								if ($file != "." && $file != "..") {
									$images[] = $file;
									$counter_check++;
								}
							}
							closedir($dir);
							$bullets_on_board = '';
							if (($navigation_way == 1) && ($counter_check > ($number_of_items_in_row * $number_of_rows))) {
								$bullets_on_board = 'bullets-on-board';
							}
						}
						?>
						<div id="myCarouselSlider<?php echo $unique_id; ?>" class="slider-carousel-outer carousel slide <?php echo $bullets_on_board . ' ' . $even_odd; ?>">
							<div class="carousel-inner <?php echo $height_slide; ?>">
								<?php
								if ($image_height == 0) {
									$image_height = 'auto';
								} else {
									$image_height = $image_height . 'px';
								}
								sort($images);
								foreach ($images as $image) {
									if ($file != "." && $file != "..") { ?>
										<?php
										$counter++;
										$image_without_extension = substr($image, 0, (strrpos($image, ".")));
										if (($number_of_items_in_row * $number_of_rows) == 1) {
											if ($counter == 1) { ?>
												<div class="item active" style="margin-left: -<?php echo $grid_spacing; ?>px;">
												<?php } else { ?>
													<div class="item" style="margin-left: -<?php echo $grid_spacing; ?>px;">
													<?php } ?>
													<?php } else {
													if (($counter % ($number_of_items_in_row * $number_of_rows) == 1)) {
														if ($counter == 1) { ?>
															<div class="item active" style="margin-left: -<?php echo $grid_spacing; ?>px;">
															<?php } else { ?>
																<div class="item" style="margin-left: -<?php echo $grid_spacing; ?>px;">
																<?php } ?>
														<?php }
												} ?>
														<ul class="thumbnails" style="width: <?php echo $slide_width; ?>%;">
															<li>
																<div class="thumbnail" style="padding-left: <?php echo $grid_spacing; ?>px; padding-bottom: <?php echo $grid_spacing; ?>px;">
																	<?php
																	echo '<img src="';
																	echo $uploads['baseurl'] . '/' . $source_folder_images_directory . '/' . $image;
																	echo '" alt="' . $image_without_extension . '" style="height: ' . $image_height . '" data-no-retina />';
																	?>
																</div>
															</li>
														</ul>
														<?php if (!($counter % 2)) {
															echo '<div class="pe-slides-separator-even">&nbsp;</div>';
														} ?>
														<?php if (!($counter % $number_of_items_in_row)) {
															echo '<div class="pe-slides-separator">&nbsp;</div>';
														} ?>
														<?php if (($counter % ($number_of_items_in_row * $number_of_rows)) == 0) { ?>
																</div>
															<?php } ?>
													<?php }
											} ?>
													<?php if ((($counter % ($number_of_items_in_row * $number_of_rows)) != 0) && ($counter >= ($number_of_items_in_row * $number_of_rows))) { ?>
															</div>
														<?php } ?>
													</div>
													<?php
													if ($counter < ($number_of_items_in_row * $number_of_rows)) { ?>
												</div>
											<?php } ?>
											<?php $counter2 = 0; ?>
											<?php if (($navigation_way == 1) && ($counter > ($number_of_items_in_row * $number_of_rows))) { ?>
												<ol class="carousel-indicators">
													<?php
													$uploads = wp_upload_dir();
													$destination_directory = $uploads['basedir'] . '/' . $source_folder_images_directory;
													$dir = opendir($destination_directory);
													if ($dir) {
														$images = array();
														while (false !== ($file = readdir($dir))) {
															if ($file != "." && $file != "..") {
																$images[] = $file;
															}
														}
														closedir($dir);
													}
													foreach ($images as $image) { ?>
														<?php $counter2++; ?>
														<?php if (($counter2 % ($number_of_items_in_row * $number_of_rows) == 1) || ($number_of_items_in_row * $number_of_rows) == 1) {
															if ($counter2 == 1) { ?>
																<li data-target="#myCarouselSlider<?php echo $unique_id; ?>" data-slide-to="0" class="active" tabindex="0"></li>
															<?php } else { ?>
																<li data-target="#myCarouselSlider<?php echo $unique_id; ?>" data-slide-to="<?php echo ($counter2 - 1) / ($number_of_items_in_row * $number_of_rows); ?>" tabindex="0"></li>
															<?php } ?>
														<?php } ?>
													<?php } ?>
												</ol>
											<?php } else if (($navigation_way == 2) && ($counter > ($number_of_items_in_row * $number_of_rows))) { ?>
												<a class="carousel-control left" href="#myCarouselSlider<?php echo $unique_id; ?>" data-slide="prev" style="margin-top: <?php echo -16 - ($grid_spacing / 2); ?>px;"><span class="sr-only"><?php _e('Previous', 'pe-easy-slider'); ?></span></a>
												<a class="carousel-control right" href="#myCarouselSlider<?php echo $unique_id; ?>" data-slide="next" style="margin-top: <?php echo -16 - ($grid_spacing / 2); ?>px;"><span class="sr-only"><?php _e('Next', 'pe-easy-slider'); ?></span></a>
												<button class="playButton btn btn-default btn-xs" type="button" style="margin-top: <?php echo -7 - ($grid_spacing / 2); ?>px;"><span class="sr-only"><?php _e('Play', 'pe-easy-slider'); ?></span></button>
												<button class="pauseButton btn btn-default btn-xs" type="button" style="margin-top: <?php echo -7 - ($grid_spacing / 2); ?>px;"><span class="sr-only"><?php _e('Pause', 'pe-easy-slider'); ?></span></button>
											<?php } ?>
							</div>
						<?php } else { ?>
							<?php _e('Entered directory doesn\'t exist, is not writable or doesn\'t contain any images.', 'pe-easy-slider'); ?>
						<?php } ?>
					<?php } ?>
					<?php echo $after_widget; ?>
					<script>
						jQuery(document).ready(
							function($) {
								$('#<?php echo $unique_id; ?> .slider-carousel-outer').carousel({
									interval: <?php echo $interval; ?>,
									pause: "<?php echo $slider_pause; ?>"
								})
								$('#<?php echo $unique_id; ?> .playButton').click(function() {
									$('#<?php echo $unique_id; ?> .slider-carousel-outer:hover').carousel('cycle');
									$('#<?php echo $unique_id; ?> .slider-carousel-outer:hover button.playButton').css("display", "none");
									$('#<?php echo $unique_id; ?> .slider-carousel-outer:hover button.pauseButton').css("display", "block");
								});
								$('#<?php echo $unique_id; ?> .pauseButton').click(function() {
									$('#<?php echo $unique_id; ?> .slider-carousel-outer:hover').carousel('pause');
									$('#<?php echo $unique_id; ?> .slider-carousel-outer:hover button.playButton').css("display", "block");
									$('#<?php echo $unique_id; ?> .slider-carousel-outer:hover button.pauseButton').css("display", "none");
								});
								$('#<?php echo $unique_id; ?> .carousel-indicators li').on('keyup', function(event) {
									if (event.which == 13) { // enter key
										jQuery(this).click();
									}
								});
							}
						);
					</script>
				<?php }

			//Admin Form

			function form($setup)
			{
				$setup = wp_parse_args((array) $setup, array(
					'title' => __('PE Easy Slider', 'pe-easy-slider'),
					'readmore' => '0',
					'source' => 'posts',
					'sticky_posts' => '0',
					'source_folder_images_directory' => 'pe-easy-slider-images',
					'number_of_all_items' => '8',
					'order_posts' => 'Date',
					'order_direction' => 'DESC',
					'navigation_way' => '1',
					'number_of_items_in_row' => '2',
					'number_of_rows' => '2',
					'image_size' => 'thumbnail',
					'styles' => '1',
					'interval' => '5000',
					'slider_pause' => 'null',
					'image_height' => '0',
					'grid_spacing' => '10',
					'category_id' => ''
				));

				$title_widget = esc_attr($setup['title']);
				$source = $setup['source'];
				$sticky_posts = $setup['sticky_posts'];
				$readmore = $setup['readmore'];
				$source_folder_images_directory = $setup['source_folder_images_directory'];
				$number_of_all_items = $setup['number_of_all_items'];
				$order_posts = $setup['order_posts'];
				$interval = $setup['interval'];
				$slider_pause = $setup['slider_pause'];
				$image_height = $setup['image_height'];
				$grid_spacing = $setup['grid_spacing'];
				$number_of_items_in_row = $setup['number_of_items_in_row'];
				$number_of_rows = $setup['number_of_rows'];
				$image_size = $setup['image_size'];
				$styles = $setup['styles'];
				$category_id = $setup['category_id'];
				?>
					<div class="pe-easy-slider-widget-options">
						<p>
							<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'pe-easy-slider'); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title_widget; ?>" />
						</p>
						<p class="source-posts">
							<label for="<?php echo $this->get_field_id('styles'); ?>"><?php _e('Styles', 'pe-easy-slider'); ?></label>
							<select name="<?php echo $this->get_field_name('styles'); ?>" id="<?php echo $this->get_field_id('styles'); ?>">
								<option value="1" <?php selected($setup['styles'], '1'); ?>><?php _e('Slider', 'pe-easy-slider'); ?></option>
								<option value="2" <?php selected($setup['styles'], '2'); ?>><?php _e('Gallery Grid', 'pe-easy-slider'); ?></option>
							</select>
						</p>
						<p class="pe-easy-slider-source-selector">
							<label for="<?php echo $this->get_field_id('source'); ?>"><?php _e('Source', 'pe-easy-slider'); ?></label>
							<select class="pe-easy-slider-source-select" name="<?php echo $this->get_field_name('source'); ?>" id="<?php echo $this->get_field_id('source'); ?>">
								<option value="posts" <?php selected($setup['source'], 'posts'); ?>><?php _e('Posts', 'pe-easy-slider'); ?></option>
								<option value="folder" <?php selected($setup['source'], 'folder'); ?>><?php _e('Folder as a source', 'pe-easy-slider'); ?></option>
							</select>
						</p>
						<p class="source-posts">
							<label for="<?php echo $this->get_field_id('sticky_posts'); ?>"><?php _e('Force display sticky posts', 'pe-easy-slider'); ?></label>
							<select class="pe-easy-slider-source-select" name="<?php echo $this->get_field_name('sticky_posts'); ?>" id="<?php echo $this->get_field_id('sticky_posts'); ?>">
								<option value="0" <?php selected($setup['sticky_posts'], '0'); ?>><?php _e('Yes', 'pe-easy-slider'); ?></option>
								<option value="1" <?php selected($setup['sticky_posts'], '1'); ?>><?php _e('No', 'pe-easy-slider'); ?></option>
							</select>
						</p>
						<p class="source-folder-images" style="margin-bottom:0;">
							<label for="<?php echo $this->get_field_id('source_folder_images_directory'); ?>"><?php _e('Image folder (inside <strong>/wp-content/uploads/</strong>)', 'pe-easy-slider'); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id('source_folder_images_directory'); ?>" name="<?php echo $this->get_field_name('source_folder_images_directory'); ?>" type="text" value="<?php echo $source_folder_images_directory; ?>" />
						</p>
						<p class="source-folder-images" style="margin-top:0;"><?php _e('<strong>Remember to create directory before you enter it above.</strong>', 'pe-easy-slider'); ?></p>
						<p class="source-posts">
							<label for="<?php echo $this->get_field_id('category_id'); ?>"><?php _e('Category (empty categories are not displayed)', 'pe-easy-slider'); ?></label>
							<select name="<?php echo $this->get_field_name('category_id'); ?>" id="<?php echo $this->get_field_id('category_id'); ?>">
								<option value=""><?php _e('All Categories', 'pe-easy-slider'); ?></option>
								<?php
								$values = array(
									'orderby' => 'name',
									'order' => 'ASC',
									'taxonomy' => 'category'
								);
								$categories = get_categories($values);
								foreach ($categories as $category) { ?>
									<option value="<?php echo $category->cat_ID; ?>" <?php selected($setup['category_id'], $category->cat_ID); ?>><?php echo $category->cat_name; ?></option>
								<?php } ?>
							</select>
						</p>
						<p>
							<label for="<?php echo $this->get_field_id('number_of_items_in_row'); ?>"><?php _e('Number of items in row', 'pe-easy-slider'); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id('number_of_items_in_row'); ?>" name="<?php echo $this->get_field_name('number_of_items_in_row'); ?>" type="text" value="<?php echo $number_of_items_in_row; ?>" />
						</p>
						<p>
							<label for="<?php echo $this->get_field_id('number_of_rows'); ?>"><?php _e('Number of rows', 'pe-easy-slider'); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id('number_of_rows'); ?>" name="<?php echo $this->get_field_name('number_of_rows'); ?>" type="text" value="<?php echo $number_of_rows; ?>" />
						</p>
						<p class="source-posts">
							<label for="<?php echo $this->get_field_id('number_of_all_items'); ?>"><?php _e('Number of all items', 'pe-easy-slider'); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id('number_of_all_items'); ?>" name="<?php echo $this->get_field_name('number_of_all_items'); ?>" type="text" value="<?php echo $number_of_all_items; ?>" />
						</p>
						<p class="source-posts">
							<label for="<?php echo $this->get_field_id('readmore'); ?>"><?php _e('Readmore', 'pe-easy-slider'); ?></label>
							<select class="pe-easy-slider-source-select" name="<?php echo $this->get_field_name('readmore'); ?>" id="<?php echo $this->get_field_id('readmore'); ?>">
								<option value="0" <?php selected($setup['readmore'], '0'); ?>><?php _e('Hide', 'pe-easy-slider'); ?></option>
								<option value="1" <?php selected($setup['readmore'], '1'); ?>><?php _e('Show', 'pe-easy-slider'); ?></option>
							</select>
						</p>
						<p class="source-posts">
							<label for="<?php echo $this->get_field_id('order_direction'); ?>"><?php _e('Posts Order Direction', 'pe-easy-slider'); ?></label>
							<select name="<?php echo $this->get_field_name('order_direction'); ?>" id="<?php echo $this->get_field_id('order_direction'); ?>">
								<option value="ASC" <?php selected($setup['order_direction'], 'ASC'); ?>><?php _e('ASC', 'pe-easy-slider'); ?></option>
								<option value="DESC" <?php selected($setup['order_direction'], 'DESC'); ?>><?php _e('DESC', 'pe-easy-slider'); ?></option>
							</select>
						</p>
						<p class="source-posts">
							<label for="<?php echo $this->get_field_id('order_posts'); ?>"><?php _e('Posts Ordering', 'pe-easy-slider'); ?></label>
							<select name="<?php echo $this->get_field_name('order_posts'); ?>" id="<?php echo $this->get_field_id('order_posts'); ?>">
								<option value="date" <?php selected($setup['order_posts'], 'date'); ?>><?php _e('Date', 'pe-easy-slider'); ?></option>
								<option value="title" <?php selected($setup['order_posts'], 'title'); ?>><?php _e('Title', 'pe-easy-slider'); ?></option>
								<option value="comment_count" <?php selected($setup['order_posts'], 'comment_count'); ?>><?php _e('Most commented', 'pe-easy-slider'); ?></option>
							</select>
						</p>
						<p>
							<label for="<?php echo $this->get_field_id('navigation_way'); ?>"><?php _e('Navigation', 'pe-easy-slider'); ?></label>
							<select name="<?php echo $this->get_field_name('navigation_way'); ?>" id="<?php echo $this->get_field_id('navigation_way'); ?>">
								<option value="1" <?php selected($setup['navigation_way'], '1'); ?>><?php _e('Bullets', 'pe-easy-slider'); ?></option>
								<option value="2" <?php selected($setup['navigation_way'], '2'); ?>><?php _e('Arrows', 'pe-easy-slider'); ?></option>
								<option value="3" <?php selected($setup['navigation_way'], '3'); ?>><?php _e('None', 'pe-easy-slider'); ?></option>
							</select>
						</p>
						<p class="source-posts">
							<label for="<?php echo $this->get_field_id('image_size'); ?>"><?php _e('Image Size', 'pe-easy-slider'); ?></label>
							<select name="<?php echo $this->get_field_name('image_size'); ?>" id="<?php echo $this->get_field_id('image_size'); ?>">
								<option value="thumbnail" <?php selected($setup['image_size'], 'thumbnail'); ?>><?php _e('thumbnail', 'pe-easy-slider'); ?></option>
								<option value="medium" <?php selected($setup['image_size'], 'medium'); ?>><?php _e('medium', 'pe-easy-slider'); ?></option>
								<option value="large" <?php selected($setup['image_size'], 'large'); ?>><?php _e('large', 'pe-easy-slider'); ?></option>
							</select>
						</p>
						<p>
							<label for="<?php echo $this->get_field_id('interval'); ?>"><?php _e('Interval in ms ( 0 - autoplay is disabled )', 'pe-easy-slider'); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id('interval'); ?>" name="<?php echo $this->get_field_name('interval'); ?>" type="text" value="<?php echo $interval; ?>" />
						</p>
						<p>
							<label for="<?php echo $this->get_field_id('slider_pause'); ?>"><?php _e('Pause on hover', 'pe-easy-slider'); ?></label>
							<select name="<?php echo $this->get_field_name('slider_pause'); ?>" id="<?php echo $this->get_field_id('slider_pause'); ?>">
								<option value="hover" <?php selected($setup['slider_pause'], 'hover'); ?>><?php _e('Yes', 'pe-easy-slider'); ?></option>
								<option value="null" <?php selected($setup['slider_pause'], 'null'); ?>><?php _e('No', 'pe-easy-slider'); ?></option>
							</select>
						</p>
						<p>
							<label for="<?php echo $this->get_field_id('image_height'); ?>"><?php _e('Image height in px ( 0 - disabled )', 'pe-easy-slider'); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id('image_height'); ?>" name="<?php echo $this->get_field_name('image_height'); ?>" type="text" value="<?php echo $image_height; ?>" />
						</p>
						<p>
							<label for="<?php echo $this->get_field_id('grid_spacing'); ?>"><?php _e('Grid Spacing (px)', 'pe-easy-slider'); ?></label>
							<input class="widefat" id="<?php echo $this->get_field_id('grid_spacing'); ?>" name="<?php echo $this->get_field_name('grid_spacing'); ?>" type="text" value="<?php echo $grid_spacing; ?>" />
						</p>
					</div>
		<?php
			}

			//Update widget

			function update($new_setup, $old_setup)
			{
				$setup = $old_setup;
				$setup['title'] = strip_tags($new_setup['title']);
				$setup['source'] = $new_setup['source'];
				$setup['sticky_posts'] = $new_setup['sticky_posts'];
				$setup['readmore'] = $new_setup['readmore'];
				$setup['source_folder_images_directory'] = $new_setup['source_folder_images_directory'];
				$setup['number_of_items_in_row']  = $new_setup['number_of_items_in_row'];
				$setup['number_of_rows']  = $new_setup['number_of_rows'];
				$setup['number_of_all_items']  = $new_setup['number_of_all_items'];
				$setup['order_posts']  = $new_setup['order_posts'];
				$setup['order_direction']  = $new_setup['order_direction'];
				$setup['navigation_way']  = $new_setup['navigation_way'];
				$setup['interval']  = strip_tags($new_setup['interval']);
				$setup['slider_pause']  = strip_tags($new_setup['slider_pause']);
				$setup['image_height']  = strip_tags($new_setup['image_height']);
				$setup['grid_spacing']  = strip_tags($new_setup['grid_spacing']);
				$setup['image_size']  = $new_setup['image_size'];
				$setup['styles']  = $new_setup['styles'];
				$setup['category_id']  = $new_setup['category_id'];
				return $setup;
			}
		}
	}

	//add CSS files
	function pe_easy_slider_css()
	{
		if (!(wp_style_is('animate.css', 'enqueued'))) {
			wp_enqueue_style('animate', plugins_url() . '/pe-easy-slider/css/animate.css');
		}
		wp_enqueue_style('pe-easy-slider', plugins_url() . '/pe-easy-slider/css/pe-easy-slider.css');
	}
	add_action('wp_enqueue_scripts', 'pe_easy_slider_css');

	//add JS
	function pe_easy_slider_js()
	{
		wp_enqueue_script('jquery');
		if (!(wp_script_is('bootstrap.js', 'enqueued') || wp_script_is('bootstrap.min.js', 'enqueued'))) {
			wp_enqueue_script('bootstrap.min', plugins_url() . '/pe-easy-slider/js/bootstrap.min.js', array('jquery'), '3.2.0', false);
		}
	}
	add_action('wp_enqueue_scripts', 'pe_easy_slider_js');

	//load widget
	function pe_easy_slider_register_widget()
	{
		return register_widget('PE_Recent_Posts_Horizontal');
	}
	add_action('widgets_init', 'pe_easy_slider_register_widget');

	//add script to admin area
	function pe_easy_slider_admin_script($hook)
	{
		if ('widgets.php' != $hook) {
			return;
		}
		wp_enqueue_script('pe-easy-slider-admin', plugins_url() . '/pe-easy-slider/js/pe-easy-slider-admin.js');
	}
	add_action('admin_enqueue_scripts', 'pe_easy_slider_admin_script');

	//enable translations
	add_action('plugins_loaded', 'pe_easy_slider_textdomain');
	function pe_easy_slider_textdomain()
	{
		load_plugin_textdomain('pe-easy-slider', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}
		?>