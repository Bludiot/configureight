<?php
/**
 * Search form class
 *
 * Creates a search form alternative
 * to that in the "Search" plugin.
 * However, the search plugin must be
 * activated for this class to operate.
 *
 * @package    Configure 8
 * @subpackage Classes
 * @category   Front
 * @since      1.0.0
 */

namespace CFE\Classes\Front;

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed direct access to this file.' );
}

// Import namespaced functions.
use function CFE_Func\{
	lang,
	text_replace
};

class Search_Form extends \pluginSearch {

	/**
	 * Show form label
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether to display the form label.
	 */
	protected $label = true;

	/**
	 * Form label text
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The text of the form label.
	 */
	protected $label_text = 'search-label-text';

	/**
	 * Show submit button
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether to display the submit button.
	 */
	protected $button = true;

	/**
	 * Submit button text
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The text of the submit button.
	 */
	protected $button_text = 'search-button-text';

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$label_text  = lang()->get( $this->label_text );
		$button_text = lang()->get( $this->button_text );

		$this->label_text  = (string) $label_text;
		$this->button_text = (string) $button_text;
	}

	/**
	 * Search form options
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function options() {

		$options = [
			'label'       => $this->label,
			'label_text'  => $this->label_text,
			'button'      => $this->button,
			'button_text' => $this->button_text
		];
		return $options;
	}

	/**
	 * Backend form
	 *
	 * Overrides the parent method.
	 *
	 * This is used by the Search plugin
	 * for an admin options from.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return null No administration form.
	 */
	public function form() {
		return null;
	}

	/**
	 * Parent output
	 *
	 * Overrides the parent method.
	 *
	 * This is used by the Search plugin
	 * to display the search form in the
	 * website sidebar.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return null
	 */
	public function siteSidebar() {
		return null;
	}

	/**
	 * Frontend output
	 *
	 * Prints the markup for the search form.
	 *
	 * This uses a `<form>` element, and a `<label>`
	 * element rather than a heading element.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return null
	 */
	public function search_form( $label, $label_text, $button, $button_text ) {

		$search = getPlugin( 'pluginSearch' );

		$html  = '<div class="form-wrap search-form-wrap">';
		$html .= '<form class="form search-form" role="search">';

		if ( $label ) {
			$html .= sprintf(
				'<label for="%s">%s</label>',
				'jspluginSearchText',
				$label_text
			);
		}

		$html .= sprintf(
			'<input type="search" id="%s" name="%s" placeholder="%s" />',
			'jspluginSearchText',
			'jspluginSearchText',
			text_replace( 'search-placeholder', $search->getValue( 'minChars' ) )
		);

		if ( $button ) {
			$html .= sprintf(
				'<input type="button" id="%s" value="%s" onClick="%s" />',
				'search-submit',
				$button_text,
				'pluginSearch()'
			);
		}

		$html .= '</form>';
		$html .= '</div>';
		$html .= $this->search_script();

		return $html;
	}

	/**
	 * Search form script
	 *
	 * Prints the JavaScript necessary
	 * to perform the search function.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function search_script() {

		?>
		<script>
			function pluginSearch() {
				var text = document.getElementById( 'jspluginSearchText' ).value;
				window.open( '<?php echo $DOMAIN_BASE ?>' + 'search/' + text, '_self' );
				return false;
			}

			document.getElementById( 'jspluginSearchText' ).onkeypress = function(e) {
				if ( ! e ) {
					e = window.event;
				}
				var keyCode = e.keyCode || e.which;
				if ( keyCode == '13' ) {
					pluginSearch();
					return false;
				}
			}
		</script>
		<?php
	}
}
