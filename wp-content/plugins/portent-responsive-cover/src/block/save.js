const { __ } = wp.i18n;
const { InnerBlocks } = wp.editor;

export default function save({ attributes }) {
		const {className, desktopImageUrl, tabletImageUrl, mobileImageUrl } = attributes;
		const defaultClass = 'wp-block-portent-block-portent-responsive-cover';

		return (
			<div className={className}>
					<span className={ defaultClass + "__background"} aria-hidden={"true"}>{null}</span>
					<picture className={defaultClass + "__picture"}>
						<source srcSet={mobileImageUrl} media="(max-width: 767px)"/>
						<source srcSet={tabletImageUrl} media="(max-width: 1023px)"/>
						<source srcSet={desktopImageUrl} media="(min-width: 1024px)"/>
						<img src={desktopImageUrl} alt=""/>
					</picture>
				<div className={ defaultClass + "__inner-blocks"}>
					<InnerBlocks.Content />
				</div>
			</div>
		);
}
