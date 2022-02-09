CREATE SCHEMA map_configs;

CREATE TABLE map_configs.controls
(
    control_id character varying COLLATE pg_catalog."default" NOT NULL,
    options json,
    abstract character varying COLLATE pg_catalog."default",
    CONSTRAINT controls_pkey PRIMARY KEY (control_id)
);

INSERT INTO map_configs.controls(control_id,options) VALUES ('home#1','{ "zoomOnStart": true }');
INSERT INTO map_configs.controls(control_id,options) VALUES ('mapmenu#1','{ "isActive": false }');
INSERT INTO map_configs.controls(control_id) VALUES ('sharemap#1');
INSERT INTO map_configs.controls(control_id) VALUES ('geoposition#1');
INSERT INTO map_configs.controls(control_id) VALUES ('print#1');
INSERT INTO map_configs.controls(control_id,options) VALUES ('about#1','{ "buttonText": "Om Origo", "title": "Om Origo", "content": "<p>Origo är ett ramverk för webbkartor. Ramverket bygger på JavaScript-biblioteket OpenLayers. Du kan använda Origo för att skapa egna webbaserade kartapplikationer.</p><br><p>Projektet drivs och underhålls av ett antal svenska kommuner. Besök gärna <a href='https://github.com/origo-map/origo' target='_blank'>Origo på GitHub</a> för mer information.</p>" }');
INSERT INTO map_configs.controls(control_id,options) VALUES ('link#1','{ "title": "Origo", "url": "https://github.com/origo-map/origo" }');
INSERT INTO map_configs.controls(control_id,options) VALUES ('legend#1','{ "labelOpacitySlider": "Opacity", "useGroupIndication" : true }');
INSERT INTO map_configs.controls(control_id,options) VALUES ('position#1','{ "title": "Web Mercator", "projections": { "EPSG:4326": "WGS84", "EPSG:3006": "Sweref99 TM" }');
INSERT INTO map_configs.controls(control_id) VALUES ('measure#1');

CREATE TABLE map_configs.footers
(
    footer_id character varying COLLATE pg_catalog."default" NOT NULL,
    img character varying COLLATE pg_catalog."default",
    url character varying COLLATE pg_catalog."default",
    text character varying COLLATE pg_catalog."default",
    abstract character varying COLLATE pg_catalog."default",
    CONSTRAINT footers_pkey PRIMARY KEY (footer_id)
);

CREATE TABLE map_configs.groups
(
    group_id character varying COLLATE pg_catalog."default" NOT NULL,
    title character varying COLLATE pg_catalog."default",
    expanded boolean NOT NULL DEFAULT false,
    abstract xml,
    groups character varying[] COLLATE pg_catalog."default",
    layers character varying[] COLLATE pg_catalog."default",
    CONSTRAINT groups_pkey PRIMARY KEY (group_id)
);

CREATE TABLE map_configs.layers
(
    layer_id character varying COLLATE pg_catalog."default" NOT NULL,
    title character varying COLLATE pg_catalog."default",
    source character varying COLLATE pg_catalog."default",
    style_layer character varying COLLATE pg_catalog."default",
    type character varying COLLATE pg_catalog."default" DEFAULT 'WMS'::character varying,
    queryable boolean DEFAULT true,
    legend boolean,
    visible boolean DEFAULT false,
    attributes json,
    icon character varying COLLATE pg_catalog."default",
    icon_extended character varying COLLATE pg_catalog."default",
    abstract character varying COLLATE pg_catalog."default",
    style_filter character varying COLLATE pg_catalog."default",
    style_config json,
    editable boolean,
    gutter integer,
    tiled boolean DEFAULT true,
    CONSTRAINT layers_pkey PRIMARY KEY (layer_id)
);

CREATE TABLE map_configs.maps
(
    map_id character varying COLLATE pg_catalog."default" NOT NULL,
    controls character varying[] COLLATE pg_catalog."default" NOT NULL DEFAULT '{home#1,mapmenu#0,sharemap,geoposition,print,about#1,kristianstad,legend#1,position#1,measure}'::character varying[],
    mapgrid boolean NOT NULL DEFAULT false,
    projectioncode character varying COLLATE pg_catalog."default" NOT NULL DEFAULT 'EPSG:3008'::character varying,
    projectionextent box NOT NULL DEFAULT '(573714.68,7702218.01),(-72234.21,6098290.04)'::box,
    extent box NOT NULL DEFAULT '(300000,6280000),(-80000,6130000)'::box,
    center point NOT NULL DEFAULT '(191000,6211212)'::point,
    zoom integer NOT NULL DEFAULT 0,
    enablerotation boolean NOT NULL DEFAULT false,
    constrainresolution boolean NOT NULL DEFAULT true,
    resolutions numeric[] NOT NULL DEFAULT '{84,42,27.9999999999999982,21,13.9999999999999991,6.9999999999999995,4.199999999999999734,2.8,2.0999999999999998667732375,1.4,0.7,0.42,0.279999999999999982,0.21,0.139999999999999991,0.111999999999999993,0.0699999999999999956,0.0419999999999999974,0.0279999999999999982,0.0209999999999999987,0.0139999999999999991,0.00699999999999999956}'::numeric[],
    proj4defs character varying[] COLLATE pg_catalog."default" DEFAULT '{EPSG:3006,EPSG:3008,EPSG:4326}'::character varying[],
    featureinfooptions json DEFAULT '{     "infowindow": "overlay"   }'::json,
    groups character varying[] COLLATE pg_catalog."default",
    layers character varying[] COLLATE pg_catalog."default",
    styles character varying[] COLLATE pg_catalog."default",
    footer character varying COLLATE pg_catalog."default",
    abstract character varying COLLATE pg_catalog."default",
    CONSTRAINT map_pk PRIMARY KEY (map_id),
    CONSTRAINT footerfk FOREIGN KEY (footer)
        REFERENCES map_configs.footers (footer_id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
);

CREATE TABLE map_configs.proj4defs
(
    code character varying COLLATE pg_catalog."default" NOT NULL,
    projection character varying COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT proj4defs_pkey PRIMARY KEY (code)
);

CREATE TABLE map_configs.services
(
    service_id character varying COLLATE pg_catalog."default" NOT NULL,
    base_url character varying COLLATE pg_catalog."default",
    abstract character varying COLLATE pg_catalog."default",
    CONSTRAINT services_pkey PRIMARY KEY (service_id)
);

CREATE TABLE map_configs.sources
(
    source_id character varying COLLATE pg_catalog."default" NOT NULL,
    service character varying COLLATE pg_catalog."default" NOT NULL DEFAULT 'qgisserver'::character varying,
    with_geometry boolean,
    fi_point_tolerance integer,
    ttl integer,
    dpi integer,
    abstract character varying COLLATE pg_catalog."default",
    CONSTRAINT sources_pkey PRIMARY KEY (source_id),
    CONSTRAINT servicefk FOREIGN KEY (service)
        REFERENCES map_configs.services (service_id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
);
