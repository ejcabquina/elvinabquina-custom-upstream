const { __ } = wp.i18n;
const { withSelect } = wp.data;

const tabSelect = ({blockCount, props}) => (
	<div>
		<pre>
			<code>{`WithSelect Tab Grabber`}</code>
		</pre>
		<p>
			{ __("Block Count:", "portent-tabbed-content")} {blockCount}
		</p>
	</div>
)

//withSelect is a higher order function (react thing) which means it is a bundle of other functions. withSelect bundles select() and Subscribe() together. The syntax for this is withSelect( {withSelect function stuff} )({function that happens after withSelect takes place})
export default withSelect( (select, ownProps) => {
	return {
		blockCount: select('core/block-editor').getBlockCount(),
	}
} )(tabSelect);
