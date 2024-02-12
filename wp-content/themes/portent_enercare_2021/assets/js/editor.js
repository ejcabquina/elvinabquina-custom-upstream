wp.domReady( () => {
	wp.blocks.unregisterBlockStyle(
		'core/button',
		[ 'default', 'outline', 'squared', 'fill' ]
	);

	wp.blocks.registerBlockStyle(
		'core/group',
		[
			{
				name: 'default',
				label: 'Default (no alignment)',
				isDefault: true,
			},
			{
				name: 'center',
				label: 'Center Align',
			},
		]
	);

	wp.blocks.registerBlockStyle(
		'core/paragraph',
		[
			{
				name: 'overline',
				label: 'Overline',
			}
		]
	);

	wp.blocks.registerBlockStyle(
		'core/image',
		[
			{
				name: 'diagram',
				label: 'Diagram'
			}
		]
	)

	wp.blocks.registerBlockStyle(
		'core/button',
		[
			{
				name: 'x-small',
				label: "Extra Small"
			},
			{
				name: 'small',
				label: 'Small'
			},
			{
				name: 'default',
				label: 'Default',
				isDefault: true,
			},
			{
				name: 'featured',
				label: 'Featured'
			},
			{
				name: 'hero',
				label: 'Hero'
			},
			{
				name: 'x-large',
				label: 'Extra Large'
			}
		]
	);

	wp.blocks.unregisterBlockStyle(
		'core/separator',
		[ 'default', 'wide', 'dots' ],
	);

	wp.blocks.unregisterBlockStyle(
		'core/quote',
		[ 'default', 'large' ]
	);

	wp.blocks.registerBlockStyle(
		'core/list',
		[
			{
				name: 'bulleted',
				label: 'Bulleted (Red)'
			},
			{
				name: 'checkmarked',
				label: 'Checkmarked (Red)'
			},
			{
				name: 'white-checkmarked',
				label: 'Checkmarked (White)'
			},
			{
				name: 'pros',
				label: 'Pros'
			},
			{
				name: 'cons',
				label: 'Cons'
			},
			{
				name: 'horizontal',
				label: 'Horizontal'
			},
			{
				name: 'grid-3',
				label: 'Grid 3'
			},
			{
				name: 'grid-4',
				label: 'Grid 4'
			}
		]
	);

	wp.blocks.registerBlockStyle(
		'core/table',
		[
			{
				name: 'feature-table',
				label: 'Feature Table'
			},
		]
	);

	wp.blocks.registerBlockStyle(
		'acf/card',
		[
			{
				name: 'plain',
				label: 'Plain'
			},
			{
				name: 'bordered',
				label: 'Bordered',
				isDefault: true,
			},
			{
				name: 'shadowed',
				label: 'Shadowed',
			}
		]
	);

	wp.blocks.registerBlockStyle(
		'acf/offer-card',
		[
			{
				name: 'plain',
				label: 'Plain'
			},
			{
				name: 'bordered',
				label: 'Bordered',
				isDefault: true,
			},
			{
				name: 'shadowed',
				label: 'Shadowed',
			}
		]
	);

	wp.blocks.registerBlockStyle(
		'acf/mega-button',
		[
			{
				name: 'plain',
				label: 'Plain',
				isDefault: true,
			},
			{
				name: 'shadowed',
				label: 'Shadowed',
			}
		]
	);

} );
