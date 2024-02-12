/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit(props) {

  const {
		TextControl, // text field
		SelectControl, // select field
	} = wp.components;
  const { withSelect, withDispatch } = wp.data;
  const { compose } = wp.compose;
  var el = wp.element.createElement;
  const { attributes } = props;
  const { setAttributes } = props;

  const PostsDropdownControl = wp.compose.compose(
    // withDispatch allows to save the selected post ID into post meta
    withDispatch( function( dispatch, props ) {
      return {
        setMetaValue: function( metaValue ) {
          dispatch( 'core/editor' ).editPost(
            { meta: { [ props.metaKey ]: metaValue } }
          );
        }
      }
    } ),
    // withSelect allows to get posts for our SelectControl and also to get the post meta value
    withSelect( function( select, props ) {
      var query = {
        per_page : -1,
        orderby : 'title',
        order : 'asc',
        status : 'publish',
        //categories : [ 5, 10, 15 ], // category ID or IDs
        //tags : 4, // tag ID, you can pass multiple too [ 4, 7 ]
        //search : 'search query',
      }
      return {
        locations: select( 'core' ).getEntityRecords( 'postType', 'gmb_location', query ),
        //metaValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ],
      }
    } ) )( function( props ) {

      // options for SelectControl
      var options = [];

      // if posts found
      if( props.locations ) {
        options.push( { value: 0, label: 'Select a location' } );
        props.locations.forEach((post) => { // simple foreach loop
          options.push({value:post.id, label:post.title.rendered});
        });
      } else {
        options.push( { value: 0, label: 'Loading...' } )
      }

      return el( SelectControl,
        {
          label: 'Select a location',
          options : options,
          onChange: function( content ) {
            setAttributes({ locationId: parseInt(content) })
            //props.setMetaValue( content );
          },
          value: attributes.locationId,
        }
      );

    }

  );

	return (
    <div>
      <PostsDropdownControl
        value={ attributes.locationId }
      />
      <ServerSideRender
        block="enercare-google-reviews/ecreviews-block"
        attributes={ attributes }
      />
    </div>
	);
}
