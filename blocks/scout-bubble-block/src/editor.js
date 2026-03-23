var registerBlockType  = window.wp.blocks.registerBlockType;
var createElement      = window.wp.element.createElement;
var useBlockProps      = window.wp.blockEditor.useBlockProps;
var InspectorControls  = window.wp.blockEditor.InspectorControls;
var RichText           = window.wp.blockEditor.RichText;
var PanelBody          = window.wp.components.PanelBody;
var PanelRow           = window.wp.components.PanelRow;
var TextControl        = window.wp.components.TextControl;
var el                 = createElement;

console.log('Scout bubble: script start');

var SCHEMES = {
    green:     { label: 'Vert scout',            border: '#007748', title: '#007748', bg: '#f9f8f5' },
    darkgreen: { label: 'Vert fonce',             border: '#005a36', title: '#005a36', bg: '#f0faf4' },
    gold:      { label: 'Or',                     border: '#d4a017', title: '#92400e', bg: '#fff8ee' },
    blue:      { label: 'Bleu',                   border: '#0065cc', title: '#0065cc', bg: '#f0f6ff' },
    red:       { label: 'Rouge',                  border: '#c0392b', title: '#b91c1c', bg: '#fff0f0' },
    grey:      { label: 'Gris',                   border: '#6a6a62', title: '#3a3a36', bg: '#f9f8f5' },
    skyblue:   { label: 'Bleu ciel',              border: '#0ea5e9', title: '#0369a1', bg: '#f0f9ff' }
};

var ICONS = [
    '\u26DC','\u269C','\uD83D\uDC3A','\uD83E\uDDAB','\uD83E\uDDED',
    '\uD83D\uDD34','\u2708','\u2693','\uD83D\uDC51','\uD83C\uDFC6',
    '\uD83C\uDFAA','\uD83C\uDF38','\uD83C\uDF96','\uD83E\uDD1D',
    '\uD83D\uDCF8','\uD83D\uDCDC','\uD83C\uDFC5','\uD83D\uDCA1',
    '\uD83C\uDFA3','\uD83D\uDD3C'
];

var ICON_LABELS = [
    'Tente','Fleur de lys','Loup','Castor','Boussole',
    'Rouge','Avion','Ancre','Couronne','Trophee',
    'Cirque','Fleur','Medaille','Poignee de main',
    'Photo','Parchemin','Medaille sport','Ampoule',
    'Peche','Fleche haut'
];

function Edit( props ) {
    var attributes    = props.attributes;
    var setAttributes = props.setAttributes;
    var scheme        = SCHEMES[ attributes.colorScheme ] || SCHEMES.green;
    var blockProps    = useBlockProps({
        style: {
            background:   scheme.bg,
            borderLeft:   '4px solid ' + scheme.border,
            borderRadius: '0 12px 12px 0',
            padding:      '16px 20px'
        }
    });

    return el(
        window.wp.element.Fragment,
        null,

        el( InspectorControls, null,

            el( PanelBody, { title: 'Couleur de la bulle', initialOpen: true },
                el( PanelRow, null,
                    el( 'div', { style: { width: '100%' } },
                        el( 'p', { style: { fontSize: '12px', color: '#757575', marginBottom: '10px' } },
                            'Choisissez une couleur :'
                        ),
                        el( 'div', {
                            style: { display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: '8px' }
                        },
                            Object.keys( SCHEMES ).map( function( key ) {
                                var s        = SCHEMES[ key ];
                                var isActive = attributes.colorScheme === key;
                                return el( 'button', {
                                    key:     key,
                                    title:   s.label,
                                    onClick: function() { setAttributes({ colorScheme: key }); },
                                    style: {
                                        width:         '100%',
                                        aspectRatio:   '1',
                                        background:    s.border,
                                        border:        isActive ? '3px solid #1e1e1e' : '3px solid transparent',
                                        borderRadius:  '8px',
                                        cursor:        'pointer',
                                        outline:       isActive ? '2px solid #fff' : 'none',
                                        outlineOffset: '-5px'
                                    }
                                } );
                            } )
                        )
                    )
                )
            ),

            el( PanelBody, { title: 'Icone / Emoji', initialOpen: false },
                el( PanelRow, null,
                    el( 'div', { style: { width: '100%' } },
                        el( TextControl, {
                            label:    'Emoji personnalise',
                            help:     'Collez un emoji, ou choisissez ci-dessous.',
                            value:    attributes.icon,
                            onChange: function( val ) { setAttributes({ icon: val }); }
                        } ),
                        el( 'div', {
                            style: { display: 'grid', gridTemplateColumns: 'repeat(5, 1fr)', gap: '6px', marginTop: '8px' }
                        },
                            ICONS.map( function( emoji, i ) {
                                var isActive = attributes.icon === emoji;
                                return el( 'button', {
                                    key:     i,
                                    onClick: function() { setAttributes({ icon: emoji }); },
                                    title:   ICON_LABELS[ i ],
                                    style: {
                                        fontSize:     '20px',
                                        background:   isActive ? scheme.bg : 'transparent',
                                        border:       isActive ? '2px solid ' + scheme.border : '2px solid transparent',
                                        borderRadius: '6px',
                                        cursor:       'pointer',
                                        padding:      '4px',
                                        lineHeight:   1
                                    }
                                }, emoji );
                            } )
                        )
                    )
                )
            )
        ),

        el( 'div', blockProps,
            el( 'div', { style: { display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '8px' } },
                attributes.icon
                    ? el( 'span', { style: { fontSize: '1.1rem' } }, attributes.icon )
                    : null,
                el( RichText, {
                    tagName:        'h3',
                    style:          { color: scheme.title, margin: 0, fontSize: '1.05rem', fontWeight: 700 },
                    value:          attributes.title,
                    onChange:       function( val ) { setAttributes({ title: val }); },
                    placeholder:    'Titre de la bulle...',
                    allowedFormats: []
                } )
            ),
            el( RichText, {
                tagName:        'p',
                style:          { margin: 0, lineHeight: 1.65, fontSize: '0.95rem' },
                value:          attributes.body,
                onChange:       function( val ) { setAttributes({ body: val }); },
                placeholder:    'Ecrivez votre contenu ici...',
                allowedFormats: [ 'core/bold', 'core/italic', 'core/link' ]
            } )
        )
    );
}

function Save( props ) {
    var attributes = props.attributes;
    var scheme     = SCHEMES[ attributes.colorScheme ] || SCHEMES.green;
    var blockProps = useBlockProps.save({
        style: {
            background:   scheme.bg,
            borderLeft:   '4px solid ' + scheme.border,
            borderRadius: '0 12px 12px 0',
            padding:      '16px 20px',
            marginBottom: '20px'
        }
    });

    return el(
        'div', blockProps,
        el( 'h3',
            { style: { color: scheme.title, margin: '0 0 8px', fontSize: '1.1rem', fontWeight: 700, display: 'flex', alignItems: 'center', gap: '8px' } },
            attributes.icon ? el( 'span', { 'aria-hidden': 'true' }, attributes.icon ) : null,
            el( RichText.Content, { value: attributes.title } )
        ),
        el( 'p',
            { style: { margin: 0, lineHeight: 1.65 } },
            el( RichText.Content, { value: attributes.body } )
        )
    );
}

console.log('Scout bubble: registering block...');

try {
    registerBlockType( 'scout-gm/info-bubble', {
        apiVersion: 3,
        title:       'Bulle d\'info',
        icon:        'format-aside',
        category:    'scout-grandmoulin',
        description: 'Bloc colore avec titre et texte.',
        keywords:    [ 'bulle', 'info', 'couleur', 'scout' ],
        attributes: {
            icon:        { type: 'string', default: '\u26DC' },
            title:       { type: 'string', default: '' },
            body:        { type: 'string', default: '' },
            colorScheme: { type: 'string', default: 'green' }
        },
        edit: Edit,
        save: Save
    } );
    console.log('Scout bubble: registered OK!');
} catch(e) {
    console.error('Scout bubble: registration failed:', e);
}
