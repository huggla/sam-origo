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
INSERT INTO map_configs.controls(control_id,options) VALUES ('about#1','{ "buttonText": "Om Origo", "title": "Om Origo", "content": "<p>Origo är ett ramverk för webbkartor. Ramverket bygger på JavaScript-biblioteket OpenLayers. Du kan använda Origo för att skapa egna webbaserade kartapplikationer.</p><br><p>Projektet drivs och underhålls av ett antal svenska kommuner. Besök gärna <a href=\"https://github.com/origo-map/origo\" target=\"_blank\">Origo på GitHub</a> för mer information.</p>" }');
INSERT INTO map_configs.controls(control_id,options) VALUES ('link#1','{ "title": "Origo", "url": "https://github.com/origo-map/origo" }');
INSERT INTO map_configs.controls(control_id,options) VALUES ('legend#1','{ "labelOpacitySlider": "Opacity", "useGroupIndication" : true }');
INSERT INTO map_configs.controls(control_id,options) VALUES ('position#1','{ "title": "Web Mercator", "projections": { "EPSG:4326": "WGS84", "EPSG:3006": "Sweref99 TM" } }');
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

INSERT INTO map_configs.footers(footer_id,img,url,text) VALUES ('origo#1','img/png/logo.png','https://github.com/origo-map/origo','Origo');

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

INSERT INTO map_configs.groups(group_id,title,expanded,layers) VALUES ('background#1','Bakgrundskartor',true,'{osm#1}');
INSERT INTO map_configs.groups(group_id,layers) VALUES ('none','{origo-mask#1}');

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
    opacity numeric(3,2) NOT NULL DEFAULT 1,
    CONSTRAINT layers_pkey PRIMARY KEY (layer_id)
);

INSERT INTO map_configs.layers(layer_id,title,type,attributes,visible,style_config,source) VALUES ('origo-cities#1','Origokommuner','GEOJSON','[ { "name": "name" } ]',true,'[ [ { "label": "Origokommuner", "circle": { "radius": 10, "stroke": { "color": "rgba(0,0,0,1)", "width": 2.5 }, "fill": { "color": "rgba(255,255,255,0.9)" } } }, { "circle": { "radius": 2.5, "stroke": { "color": "rgba(0,0,0,0)", "width": 1 }, "fill": { "color": "rgba(37,129,196,1)" } } } ] ]','data/origo-cities-3857.geojson');
INSERT INTO map_configs.layers(layer_id,title,type,visible,style_config,source,queryable,opacity) VALUES ('origo-mask#1','Origo-mask','GEOJSON',true,'[ [ { "stroke": { "color": "rgba(0,0,0,1.0)" }, "fill": { "color": "rgba(0,0,0,1.0)" } } ] ]','data/origo-mask-3857.geojson',false,0.25);
INSERT INTO map_configs.layers(layer_id,title,type,visible,icon,queryable) VALUES ('osm#1','OpenStreetMap','OSM',true,'img/png/osm.png',false);

CREATE TABLE map_configs.maps
(
    map_id character varying COLLATE pg_catalog."default" NOT NULL,
    controls character varying[] COLLATE pg_catalog."default" NOT NULL DEFAULT '{home#1,mapmenu#1,sharemap#1,geoposition#1,print#1,about#1,link#1,legend#1,position#1,measure#1}'::character varying[],
    mapgrid boolean NOT NULL DEFAULT true,
    projectioncode character varying COLLATE pg_catalog."default" NOT NULL DEFAULT 'EPSG:3857'::character varying,
    projectionextent box NOT NULL DEFAULT '(-20026376.39,-20048966.10),(20026376.39,20048966.10)'::box,
    extent box NOT NULL DEFAULT '(-20026376.39,-20048966.10),(20026376.39,20048966.10)'::box,
    center point NOT NULL DEFAULT '(1770000,8770000)'::point,
    zoom integer NOT NULL DEFAULT 7,
    enablerotation boolean NOT NULL DEFAULT true,
    constrainresolution boolean NOT NULL DEFAULT true,
    resolutions numeric[] NOT NULL DEFAULT '{156543.03,78271.52,39135.76,19567.88,9783.94,4891.97,2445.98,1222.99,611.50,305.75,152.87,76.437,38.219,19.109,9.5546,4.7773,2.3887,1.1943,0.5972}'::numeric[],
    proj4defs character varying[] COLLATE pg_catalog."default" DEFAULT '{EPSG:3006}'::character varying[],
    featureinfooptions json DEFAULT '{ "infowindow": "overlay" }'::json,
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

INSERT INTO map_configs.maps(map_id,footer,layers,groups) VALUES ('origo-cities#1','origo#1','{origo-cities#1}','{none,background#1}');

CREATE TABLE map_configs.proj4defs
(
    code character varying COLLATE pg_catalog."default" NOT NULL,
    projection character varying COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT proj4defs_pkey PRIMARY KEY (code)
);

INSERT INTO map_configs.proj4defs(code,projection) VALUES ('EPSG:3006','+proj=utm +zone=33 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs');

CREATE TABLE map_configs.services
(
    service_id character varying COLLATE pg_catalog."default" NOT NULL,
    base_url character varying COLLATE pg_catalog."default",
    abstract character varying COLLATE pg_catalog."default",
    CONSTRAINT services_pkey PRIMARY KEY (service_id)
);

INSERT INTO map_configs.services(service_id,base_url) VALUES ('origo-cities#1','data/origo-cities-3857.geojson');

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
