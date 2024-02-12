const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { InspectorControls, InnerBlocks } = wp.editor;
const { MediaUpload, MediaUploadCheck } = wp.blockEditor;
const { PanelBody, Button, ResponsiveWrapper, Spinner } = wp.components;
const { compose } = wp.compose;
const { withSelect } = wp.data;

const ALLOWED_MEDIA_TYPES = [ 'image' ];

class responsiveCoverEdit extends Component {
	render() {
		const { className, attributes, setAttributes, desktopImage, tabletImage, mobileImage } = this.props;
		const { desktopImageId, tabletImageId, mobileImageId, desktopImageUrl, tabletImageUrl, mobileImageUrl } = attributes;
		const instructions = <p>{ __( 'To edit the background image, you need permission to upload media.', 'portent-responsive-cover' ) }</p>;
		const defaultClass = 'wp-block-portent-block-portent-responsive-cover';

		const onUpdateImage = ( image, type ) => {
			switch (type) {
				case 'desktop':
					setAttributes( {
						desktopImageId: image.id,
					} );
					break;
				case 'tablet':
					setAttributes( {
						tabletImageId: image.id,
					} );
					break;
				case 'mobile':
					setAttributes( {
						mobileImageId: image.id,
					} );
					break;
			}
		};

		const onRemoveImage = (type) => {
			switch (type) {
				case 'desktop':
					setAttributes( {
						desktopImageId: undefined,
					} );
					break;
				case 'tablet':
					setAttributes( {
						tabletImageId: undefined,
					} );
					break;
				case 'mobile':
					setAttributes( {
						mobileImageId: undefined,
					} );
					break;
			}
		}

		//Set urls
		{!!desktopImageId && desktopImage &&
			setAttributes({ desktopImageUrl: desktopImage.source_url})
		}

		{!!tabletImageId && tabletImage &&
		setAttributes({ tabletImageUrl: tabletImage.source_url})
		}

		{!!mobileImageId && mobileImage &&
		setAttributes({ mobileImageUrl: mobileImage.source_url})
		}

		console.log(this.props);

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody
						title={ __( 'Desktop Image', 'portent-responsive-cover' ) }
						initialOpen={ true }
					>
						<div className="wp-block-image-selector-example-image">
							<MediaUploadCheck fallback={ instructions }>
								<MediaUpload
									title={ __( 'Desktop Image', 'portent-responsive-cover' ) }
									onSelect={ (media) =>
										onUpdateImage(media, 'desktop')
									}
									allowedTypes={ ALLOWED_MEDIA_TYPES }
									value={ desktopImageId }
									render={ ( { open } ) => (
										<Button
											className={ ! desktopImageId ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview' }
											onClick={ open }>
											{ ! desktopImageId && ( __( 'Set Desktop image', 'portent-responsive-cover' ) ) }
											{ !! desktopImageId && ! desktopImage && <Spinner /> }
											{ !! desktopImageId && desktopImage &&
											<ResponsiveWrapper
												naturalWidth={ desktopImage.media_details.width }
												naturalHeight={ desktopImage.media_details.height }
											>
												<img src={ desktopImage.source_url } alt={ __( 'Desktop image', 'portent-responsive-cover' ) } />
											</ResponsiveWrapper>
											}
										</Button>
									) }
								/>
							</MediaUploadCheck>
							{ !! desktopImageId &&
							<MediaUploadCheck>
								<Button onClick={ () => onRemoveImage('desktop') } isLink isDestructive>
									{ __( 'Remove desktop image', 'portent-responsive-cover' ) }
								</Button>
							</MediaUploadCheck>
							}
						</div>
					</PanelBody>
					<PanelBody
						title={ __( 'Tablet Image', 'portent-responsive-cover' ) }
						initialOpen={ true }
					>
						<div className="wp-block-image-selector-example-image">
							<MediaUploadCheck fallback={ instructions }>
								<MediaUpload
									title={ __( 'Tablet Image', 'portent-responsive-cover' ) }
									onSelect={ (media) =>
										onUpdateImage(media, 'tablet')
									}
									allowedTypes={ ALLOWED_MEDIA_TYPES }
									value={ tabletImageId }
									render={ ( { open } ) => (
										<Button
											className={ ! tabletImageId ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview' }
											onClick={ open }>
											{ ! tabletImageId && ( __( 'Set Tablet image', 'portent-responsive-cover' ) ) }
											{ !! tabletImageId && ! tabletImage && <Spinner /> }
											{ !! tabletImageId && tabletImage &&
											<ResponsiveWrapper
												naturalWidth={ tabletImage.media_details.width }
												naturalHeight={ tabletImage.media_details.height }
											>
												<img src={ tabletImage.source_url } alt={ __( 'Tablet image', 'portent-responsive-cover' ) } />
											</ResponsiveWrapper>
											}
										</Button>
									) }
								/>
							</MediaUploadCheck>
							{ !! tabletImageId &&
							<MediaUploadCheck>
								<Button onClick={ () => onRemoveImage('tablet') } isLink isDestructive>
									{ __( 'Remove tablet image', 'portent-responsive-cover' ) }
								</Button>
							</MediaUploadCheck>
							}
						</div>
					</PanelBody>
					<PanelBody
						title={ __( 'Mobile Image', 'portent-responsive-cover' ) }
						initialOpen={ true }
					>
						<div className="wp-block-image-selector-example-image">
							<MediaUploadCheck fallback={ instructions }>
								<MediaUpload
									title={ __( 'Desktop Image', 'portent-responsive-cover' ) }
									onSelect={ (media) =>
										onUpdateImage(media, 'mobile')
									}
									allowedTypes={ ALLOWED_MEDIA_TYPES }
									value={ mobileImageId }
									render={ ( { open } ) => (
										<Button
											className={ ! mobileImageId ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview' }
											onClick={ open }>
											{ ! mobileImageId && ( __( 'Set Mobile image', 'portent-responsive-cover' ) ) }
											{ !! mobileImageId && ! mobileImage && <Spinner /> }
											{ !! mobileImageId && mobileImage &&
											<ResponsiveWrapper
												naturalWidth={ mobileImage.media_details.width }
												naturalHeight={ mobileImage.media_details.height }
											>
												<img src={ mobileImage.source_url } alt={ __( 'Mobile image', 'portent-responsive-cover' ) } />
											</ResponsiveWrapper>
											}
										</Button>
									) }
								/>
							</MediaUploadCheck>
							{ !! mobileImageId &&
							<MediaUploadCheck>
								<Button onClick={ () => onRemoveImage('mobile') } isLink isDestructive>
									{ __( 'Remove mobile image', 'portent-responsive-cover' ) }
								</Button>
							</MediaUploadCheck>
							}
						</div>
					</PanelBody>
				</InspectorControls>
				<div className={className}>
					<span className={ defaultClass + "__background"} aria-hidden={"true"}>{null}</span>
					<picture className={defaultClass + "__picture"}>
						{!!mobileImageId && mobileImage &&
						<source data-image="mobile" srcSet={mobileImage.source_url} media="(max-width: 767px)"/>
						}

						{!!tabletImageId && tabletImage &&
						<source data-image="tablet" srcSet={tabletImage.source_url} media="(max-width: 1023px)"/>
						}

						{!!desktopImageId && desktopImage &&
						<source data-image="desktop" srcSet={desktopImage.source_url} media="(min-width: 1024px)"/>
						}

						{!!desktopImageId && desktopImage &&
						<img src={desktopImage.source_url} alt=""/>
						}
					</picture>
					<InnerBlocks />
				</div>
			</Fragment>
		);
	}
}

export default compose(
	withSelect( ( select, props ) => {
		const { getMedia } = select( 'core' );
		const { desktopImageId, tabletImageId, mobileImageId } = props.attributes;

		return {
			desktopImage: desktopImageId ? getMedia( desktopImageId ) : null,
			tabletImage: tabletImageId ? getMedia( tabletImageId ) : null,
			mobileImage: mobileImageId ? getMedia( mobileImageId ) : null
		};
	} ),
)( responsiveCoverEdit );
