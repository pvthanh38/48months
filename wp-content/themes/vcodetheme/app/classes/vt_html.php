<?php

class vt_html {
	static function atts( $atts, $exclude = [] ) {
		$_atts = [];

		if (is_array($exclude)){
		    foreach ($exclude as $ex){
		        if ($atts[$ex])
		            unset($atts[$ex]);
            }
        }

        foreach ( $atts as $key => $value ) {
			array_push( $_atts, "$key = '$value'" );
		}

		return implode( ' ', $_atts );
	}

	static function img( $url, $atts = [] ) {
		$atts['src'] = $url;
		if ($atts['url']){
		    ?>
            <a href="<?php echo $atts['url'] ?>">
                <img <?php echo self::atts( $atts, ['url'] ); ?>/>
            </a>
            <?php
        }
        else{
		    ?>
            <img <?php echo self::atts( $atts, ['url']); ?>/>
            <?php
        }
		?>
        <?php
	}

	static function button( $label, $atts = [] ) {
		$atts = array_merge( [
			'class' => '',
		], $atts );
		$atts['class'] .= ' vt-button';
		?>
        <button <?php echo self::atts( $atts ); ?>><?php echo $label; ?></button>
		<?php
	}

	static function select( $options, $atts ) {
		$wrapperClass = $atts['wrapperClass'] ? $atts['wrapperClass'] : '';
		?>
        <label class="vt-select <?php echo $wrapperClass; ?>">
			<?php if ( ! empty( $atts['label'] ) ) {
				?>
                <span><?php echo $atts['label']; ?></span>
				<?php
			} ?>
            <select <?php echo self::atts( $atts ); ?>>
				<?php foreach ( $options as $value => $text ) {
					?>
                    <option <?php echo $atts['selected'] === $value ? 'selected' : ''; ?>
                            value="<?php echo $value; ?>"><?php echo $text; ?></option>
					<?php
				} ?>
            </select>
            <span class="arrow_box"></span>
        </label>
		<?php
	}
}