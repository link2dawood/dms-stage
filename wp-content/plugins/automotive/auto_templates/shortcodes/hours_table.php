<?php
/*
	Automotive Hours Table Shortcode Template File
	To overwrite this file copy it to automotive-child/auto_templates/shortcodes/hours_table.php

	Version: 14.1
	Help: https://support.themesuite.com/kb/faq.php?id=9
 */

echo '<table class="table table-bordered no-border font-12px hours_table ' . ( ! empty( $extra_class ) ? sanitize_html_classes( $extra_class ) : "" ) . '">';

if(!empty($title)){
	echo '<thead>
				<tr>
					<td colspan="2"><strong>' . esc_html( $title ) . '</strong></td>
				</tr>
			</thead>';
}

echo '<tbody>
			<tr>
				<td>' . __( 'Mon', 'listings' ) . ':</td>
				<td>' . esc_html( $mon ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Tue', 'listings' ) . ':</td>
				<td>' . esc_html( $tue ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Wed', 'listings' ) . ':</td>
				<td>' . esc_html( $wed ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Thu', 'listings' ) . ':</td>
				<td>' . esc_html( $thu ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Fri', 'listings' ) . ':</td>
				<td>' . esc_html( $fri ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Sat', 'listings' ) . ':</td>
				<td>' . esc_html( $sat ) . '</td>
			</tr>
			<tr>
				<td>' . __( 'Sun', 'listings' ) . ':</td>
				<td>' . esc_html( $sun ) . '</td>
			</tr>
		</tbody>
		</table>';