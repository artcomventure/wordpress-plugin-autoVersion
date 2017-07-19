<div class="wrap">
	<h2><?php _e( 'Auto-Versioning CSS and JavaScript files', 'autoversion' ); ?></h2>

	<p><?php _e( 'In other words: Cache busting when <i>these</i> files are changed.', 'autoversion' ) ?></p>

	<form id="autoversion-settings-form" method="post" action="options.php">
		<?php settings_fields( 'autoversion' );

		$settings = autoversion_get_settings(); ?>

		<table class="form-table">
			<tbody>

			<?php foreach ( array( 'CSS', 'js' => 'JavaScript' ) as $type => $label ) :
				if ( is_numeric( $type ) ) $type = strtolower( $label ); ?>

				<tr valign="top">
					<th scope="row">
						<?php _e( $label, 'autoversion' ); ?>:
					</th>
					<td>
						<select name="autoversion[<?php echo $type ?>][status]">
							<option value="0" title=""<?php selected( 0, $settings[$type]['status'] ) ?>><?php _e( 'Disable', 'autoversion' ); ?></option>
							<option value="1"<?php selected( 1, $settings[$type]['status'] ) ?>
							        title="<?php _e( '... except files with exitsting version numbers.', 'autoversion' ) ?>">
								<?php _e( 'Set', 'autoversion' ); ?>
							</option>
							<option value="2"<?php selected( 2, $settings[$type]['status'] ) ?>
							        title="<?php _e( '... incl. overriding existing ones.', 'autoversion' ) ?>">
								<?php _e( 'Force', 'autoversion' ); ?>
							</option>
						</select>

						<?php printf( __( '<span class="auto">auto</span> version number <span class="version-number">to %s</span>', 'autoversion' ), '<input name="autoversion[' . $type . '][ver]" type="text" class="regular-text" value="' . $settings[$type]['ver']. '" placeholder="' . __( "file's modification timestamp", 'autoversion' ) . '" />' ) ?>

						<p class="description"></p>
					</td>
				</tr>

			<?php endforeach; ?>

			</tbody>
		</table>

		<table class="form-table">
			<tbody>
			<tr valign="top">
				<th scope="row">
					<?php _e( 'Ignore <i>these</i> files', 'autoversion' ); ?>:
				</th>
				<td>
					<?php foreach ( array(
						__( 'WordPress' ) => array( 'wp-admin' => array( 'Name' => '<code>ROOT/wp-admin/*</code>' ), 'wp-includes' => array( 'Name' => '<code>ROOT/wp-includes/*</code>' ) ),
						'Plugins' => get_plugins(),
						'Themes' => wp_get_themes()
					) as $type => $list ) :
						if ( !count( $list ) ) continue;

						printf( '<h4 style="margin-top: 5px;">%s</h4>', __( $type, 'autoversion' ) ); ?>
						<ul>
						<?php foreach ( $list as $file => $data ) : ?>
							<li>
								<label style="display: block;">
									<input type="checkbox" class="regular-checkbox"
									       name="autoversion[ignore][<?php echo strtolower( $type ) ?>][<?php echo $file ?>]"
									       value="1"<?php checked( $settings['ignore'][strtolower( $type )][$file] ); ?> />
									<?php echo $data['Name']; ?>
								</label>
							</li>
						<?php endforeach; ?>
						</ul>
					<?php endforeach; ?>
				</td>
			</tr>

			</tbody>
		</table>

		<?php submit_button(); ?>

	</form>
</div>
