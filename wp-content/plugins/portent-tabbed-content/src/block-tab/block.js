/**
 * BLOCK: portent-tabbed-content
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
import { InnerBlocks, useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { TextControl, ToggleControl, Button, Panel, PanelBody, PanelRow, Spinner } from '@wordpress/components';
import { useState } from '@wordpress/element';
const ALLOWED_MEDIA_TYPES = [ 'image' ];

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'portent/block-tabbed-content--tab', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Tab' ), // Block title.
	description: __( 'Inner child block type for tabbed content.' ),
	parent: [ 'portent/block-tabbed-content' ],
	icon: 'tablet', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'Portent' ),
		__( 'Enercare' ),
		__( 'Tab' ),
	],
	attributes: {
		title: {
			type: 'string'
		},
		iconid : {
			type: 'string',
		},
		tabid: {
			type: 'string'
		},
		defaultTab: {
			type: 'boolean',
			default: false
		},
		tabAnchor: {
			type: 'string',
			default: ''
		}
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Component.
	 */
	edit: ( props) => {
		const { attributes:{ title, iconid, tabid, defaultTab, tabAnchor }, setAttributes} = props;
		props.setAttributes({tabid: props.clientId})
		const blockProps = useBlockProps();

		const onUpdateImage = ( image ) => {
			setAttributes( {
				iconid: image.url,
			} );
		};

		const onRemoveImage = () => {
			setAttributes( {
				iconid: undefined,
			} );
		};

		return (
			<div className="block-tabbed-content__panel" data-tab={props.attributes.tabid} data-interface="tab-panel">
				<InspectorControls key="setting">
					<div id="tab-controls">
						<Panel header="">
							<PanelBody title="Tab Settings" initialOpen={ true }>
								<PanelRow>
									<TextControl
										label = { __( 'Tab Title', 'portent-tabbed-content' ) }
										help = { __('Tab title will be used to populate tab switching button names', 'portent-tabbed-content' ) }
										value = { title }
										onChange = { title => setAttributes( {title} ) }
										/>
								</PanelRow>
								<PanelRow>
									<TextControl
										label = { __( 'Tab Anchor', 'portent-tabbed-content' ) }
										help = { __('The tab anchor will be used for deep-link. Only dashes', 'portent-tabbed-content' ) }
										value = { tabAnchor }
										onChange = { tabAnchor => setAttributes( {tabAnchor} ) }
									/>
								</PanelRow>
								<PanelRow>
									<label className="portent-tabbed-content__tab-icon-label">Tab Icon</label>
									<MediaUploadCheck>
										<MediaUpload
											title={ __( 'Tab Icon', 'portent-tabbed-content' ) }
											onSelect={ onUpdateImage }
											allowedTypes={ ALLOWED_MEDIA_TYPES }
											value={ props.attributes.iconid }
											render={ ( { open } ) => (
												<Button
													className={ __('portent-tabbed-content__tab-icon-label', 'portent-tabbed-content')}
													onClick={ open }>
													{ ! iconid && ( __( 'Set Tab Icon', 'portent-tabbed-content' ) ) }
													{ !! iconid &&
														<img width="90" height="90" src={ iconid } alt={ __( 'Tab Icon', 'portent-tabbed-content' ) } />
													}
												</Button>
											) }
										/>
									</MediaUploadCheck>
									{ !! iconid &&
									<MediaUploadCheck>
										<Button onClick={ onRemoveImage } isLink isDestructive>
											{ __( 'Remove Tab Icon', 'image-selector-example' ) }
										</Button>
									</MediaUploadCheck>
									}
								</PanelRow>
								<PanelRow>
									<ToggleControl
										label="Default Display Tab?"
										help={defaultTab ? "Tab will display on page load" : "Tab will not show on page load"}
										checked={defaultTab}
										onChange={() => setAttributes({ defaultTab: !defaultTab })}
									/>
								</PanelRow>
							</PanelBody>
						</Panel>
					</div>
				</InspectorControls>
					<h3 className={"block-tabbed-content__tab__title"}>{props.attributes.title}</h3>
					<InnerBlocks orientation="horizontal"/>
			</div>
		);
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Frontend HTML.
	 */
	save: ( props ) => {
		return (
			<div className="block-tabbed-content__panel" data-default={props.attributes.defaultTab}>
				<div class="block-tabbed-content__panel__mobile-toggle">
					<button id={"tab_toggle--" + props.attributes.tabid} className="block-tabbed-content__panel__toggle" data-anchor={props.attributes.tabAnchor} data-tab={props.attributes.tabid} aria-expanded="false" aria-controls="sect1">
						<img class="block-tabbed-content__panel__icon" width="20" height="20" src={props.attributes.iconid} />
						<h2 className="block-tabbed-content__panel__title">{props.attributes.title}</h2>
						<svg className="block-tabbed-content__panel__arrow" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M24 24H0V0h24v24z" fill="none" opacity=".87"/><path d="M7.38 21.01c.49.49 1.28.49 1.77 0l8.31-8.31c.39-.39.39-1.02 0-1.41L9.15 2.98c-.49-.49-1.28-.49-1.77 0s-.49 1.28 0 1.77L14.62 12l-7.25 7.25c-.48.48-.48 1.28.01 1.76z"/></svg>
					</button>
				</div>
				<section className="block-tabbed-content__tab-content" data-tab={props.attributes.tabid} aria-labelledby={"tab_toggle--" + props.attributes.tabid} hidden="">
					<InnerBlocks.Content />
				</section>
			</div>
		);
	},
	deprecated: [
		{
			attributes: {
				title: {
					type: 'string'
				},
				iconid : {
					type: 'string',
				},
				tabid: {
					type: 'string'
				},
				defaultTab: {
					type: 'boolean',
					default: false
				},
				tabAnchor: {
					type: 'string',
				}
			},
			save: ( props ) => {
				return (
					<div className="block-tabbed-content__panel" data-default={props.attributes.defaultTab}>
						<div class="block-tabbed-content__panel__mobile-toggle">
							<button id={"tab_toggle--" + props.attributes.tabid} className="block-tabbed-content__panel__toggle" data-anchor={props.attributes.tabAnchor} data-tab={props.attributes.tabid} aria-expanded="false" aria-controls="sect1">
								<img class="block-tabbed-content__panel__icon" width="20" height="20" src={props.attributes.iconid} />
								<h2 className="block-tabbed-content__panel__title">{props.attributes.title}</h2>
								<svg className="block-tabbed-content__panel__arrow" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M24 24H0V0h24v24z" fill="none" opacity=".87"/><path d="M7.38 21.01c.49.49 1.28.49 1.77 0l8.31-8.31c.39-.39.39-1.02 0-1.41L9.15 2.98c-.49-.49-1.28-.49-1.77 0s-.49 1.28 0 1.77L14.62 12l-7.25 7.25c-.48.48-.48 1.28.01 1.76z"/></svg>
							</button>
						</div>
						<section className="block-tabbed-content__tab-content" data-tab={props.attributes.tabid} aria-labelledby={"tab_toggle--" + props.attributes.tabid} hidden="">
							<InnerBlocks.Content />
						</section>
					</div>
				);
			}
		},
		{
			attributes: {
				title: {
					type: 'string'
				},
				iconid : {
					type: 'string',
				},
				tabid: {
					type: 'string'
				}
			},
			save: ( props ) => {
				//const blockProps = useBlockProps.save();
				return (
					<div className="block-tabbed-content__panel">
						<h3 className="block-tabbed-content__tab__title" data-tab={props.attributes.tabid}>{props.attributes.title}</h3>
						<div className="block-tabbed-content__tab-content" data-tab={props.attributes.tabid}>
							<InnerBlocks.Content />
						</div>
					</div>
				);
			}
		},
		{
			attributes: {
				title: {
					type: 'string'
				},
				iconid : {
					type: 'string',
				},
				tabid: {
					type: 'string'
				}
			},
			save: ( props ) => {
				//const blockProps = useBlockProps.save();
				return (
					<div className="block-tabbed-content__panel">
						<div class="block-tabbed-content__panel__mobile-toggle">
							<button id={"tab_toggle--" + props.attributes.tabid} className="block-tabbed-content__panel__toggle" data-tab={props.attributes.tabid} aria-expanded="false" aria-controls="sect1">
								<img class="block-tabbed-content__panel__icon" width="20" height="20" src={props.attributes.iconid} />
								<h2 className="block-tabbed-content__panel__title">{props.attributes.title}</h2>
								<svg className="block-tabbed-content__panel__arrow" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M24 24H0V0h24v24z" fill="none" opacity=".87"/><path d="M7.38 21.01c.49.49 1.28.49 1.77 0l8.31-8.31c.39-.39.39-1.02 0-1.41L9.15 2.98c-.49-.49-1.28-.49-1.77 0s-.49 1.28 0 1.77L14.62 12l-7.25 7.25c-.48.48-.48 1.28.01 1.76z"/></svg>
							</button>
						</div>
						<section className="block-tabbed-content__tab-content" data-tab={props.attributes.tabid} aria-labelledby={"tab_toggle--" + props.attributes.tabid} hidden="">
							<InnerBlocks.Content />
						</section>
					</div>
				);
			}
		}
	]
} );
