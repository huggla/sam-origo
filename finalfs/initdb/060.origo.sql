CREATE SCHEMA map_configs;

CREATE TABLE map_configs.controls
(
    control_id character varying COLLATE pg_catalog."default" NOT NULL,
    options json,
    info character varying COLLATE pg_catalog."default",
    CONSTRAINT controls_pkey PRIMARY KEY (control_id)
);

INSERT INTO map_configs.controls(control_id,options,info) VALUES ('home#1','{ "zoomOnStart": true }','Ställer in kartans utbredning till den som angivits i alternativen för kontrollen.');
INSERT INTO map_configs.controls(control_id,options,info) VALUES ('mapmenu#1','{ "isActive": false }','Skapar en meny uppe till höger för kontroller.');
INSERT INTO map_configs.controls(control_id,info) VALUES ('sharemap#1','Skapar en delbar länk till kartan. Aktuell utbredning och zoom, synliga lager och kartnålen (om tillämpligt) kommer att delas. Om ett objekt på kartan väljs, kommer objektets ID att finnas i länken som gör att kartan zoomar in på den när den laddas. Detta gäller för WFS, Geojson, Topojson och AGS Feature lager. Sharemap-kontrollen kommer också med möjlighet att spara karttillstånd på servern (kräver Origo Server). Ett sparat karttillstånd hämtas med ett ID istället för en URL.');
INSERT INTO map_configs.controls(control_id,info) VALUES ('geoposition#1','Lägger till en knapp som när du klickar på den centrerar och zoomar kartan till den aktuella positionen. Genom att klicka på knappen en andra gång aktiveras spårningsläget (om enableTracking har satts till true).');
INSERT INTO map_configs.controls(control_id,info) VALUES ('print#1','Lägger till en utskriftskontroll.');
INSERT INTO map_configs.controls(control_id,options,info) VALUES ('about#1','{ "buttonText": "Om Origo", "title": "Om Origo", "content": "<p>Origo är ett ramverk för webbkartor. Ramverket bygger på JavaScript-biblioteket OpenLayers. Du kan använda Origo för att skapa egna webbaserade kartapplikationer.</p><br><p>Projektet drivs och underhålls av ett antal svenska kommuner. Besök gärna <a href=\"https://github.com/origo-map/origo\" target=\"_blank\">Origo på GitHub</a> för mer information.</p>" }','Lägger till en om-kartkontroll. En knapp läggs till i menyn. När du klickar på knappen kommer ett popup-fönster att visa allmän information om kartan. OBS - kräver mapmenu-kontrollen.');
INSERT INTO map_configs.controls(control_id,options,info) VALUES ('link#1','{ "title": "Origo", "url": "https://github.com/origo-map/origo" }','Lägger till en knapp på kartmenyn som när den klickas öppnar en ny webbläsarflik med den angivna webbadressen.');
INSERT INTO map_configs.controls(control_id,options,info) VALUES ('legend#1','{ "labelOpacitySlider": "Opacity", "useGroupIndication" : true }','Lägger till en legend i menyn och som en kartförklaring till kartan.');
INSERT INTO map_configs.controls(control_id,options,info) VALUES ('position#1','{ "title": "Web Mercator", "projections": { "EPSG:4326": "WGS84", "EPSG:3006": "Sweref99 TM" } }','Kontroll för att visa koordinater. Musens position och mittposition på kartan kan växlas. Koordinater kan sökas på i mittpositionsläget.');
INSERT INTO map_configs.controls(control_id,info) VALUES ('measure#1','Lägger till en mätningskontroll. Mät längd, area eller höjd (kräver tillgång till extern höjddatawebbtjänst) i kartan.');

CREATE TABLE map_configs.footers
(
    footer_id character varying COLLATE pg_catalog."default" NOT NULL,
    img character varying COLLATE pg_catalog."default",
    url character varying COLLATE pg_catalog."default",
    text character varying COLLATE pg_catalog."default",
    info character varying COLLATE pg_catalog."default",
    CONSTRAINT footers_pkey PRIMARY KEY (footer_id)
);

INSERT INTO map_configs.footers(footer_id,img,url,text,info) VALUES ('origo#1','img/png/logo.png','https://github.com/origo-map/origo','Origo','En sidfot som vid klick öppnar Origoprojektets Github-sida i en ny flik.');

CREATE TABLE map_configs.groups
(
    group_id character varying COLLATE pg_catalog."default" NOT NULL,
    title character varying COLLATE pg_catalog."default",
    expanded boolean NOT NULL DEFAULT false,
    abstract character varying COLLATE pg_catalog."default",
    groups character varying[] COLLATE pg_catalog."default",
    layers character varying[] COLLATE pg_catalog."default",
    info character varying COLLATE pg_catalog."default",
    CONSTRAINT groups_pkey PRIMARY KEY (group_id)
);

INSERT INTO map_configs.groups(group_id,title,expanded,layers,info) VALUES ('background#1','Bakgrundskartor',true,'{osm#1}','Grupp som innehåller alla bakgrundslager.');
INSERT INTO map_configs.groups(group_id,layers,info) VALUES ('none#1','{origo-mask#1}','Grupp som inte visas i lagerträdet.');

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
    featureinfolayer character varying COLLATE pg_catalog."default",
    info character varying COLLATE pg_catalog."default",
    categories character varying[] COLLATE pg_catalog."default",
    format character varying COLLATE pg_catalog."default" DEFAULT 'image/png'::character varying,
    adusers character varying[] COLLATE pg_catalog."default",
    adgroups character varying[] COLLATE pg_catalog."default",
    layers character varying[] COLLATE pg_catalog."default",
    layertype character varying COLLATE pg_catalog."default",
    clusteroptions json,
    maxscale integer,
    minscale integer,
    clusterstyle json,
    attribution character varying COLLATE pg_catalog."default",
    CONSTRAINT layers_pkey PRIMARY KEY (layer_id)
);

INSERT INTO map_configs.layers(layer_id,title,type,attributes,visible,style_config,source,info) VALUES ('origo-cities#1','Origokommuner','GEOJSON','[ { "name": "name" } ]',true,'[ [ { "label": "Origokommuner", "circle": { "radius": 10, "stroke": { "color": "rgba(0,0,0,1)", "width": 2.5 }, "fill": { "color": "rgba(255,255,255,0.9)" } } }, { "circle": { "radius": 2.5, "stroke": { "color": "rgba(0,0,0,0)", "width": 1 }, "fill": { "color": "rgba(37,129,196,1)" } } } ] ]','data/origo-cities-3857.geojson','Lager som visar kommuner delaktiga i Origoprojektet.');
INSERT INTO map_configs.layers(layer_id,title,type,visible,style_config,source,queryable,opacity,info) VALUES ('origo-mask#1','Origo-mask','GEOJSON',true,'[ [ { "stroke": { "color": "rgba(0,0,0,1.0)" }, "fill": { "color": "rgba(0,0,0,1.0)" } } ] ]','data/origo-mask-3857.geojson',false,0.25,'Lager som tonar ner de delar av kartan som inte utgör del av en Origokommun.');
INSERT INTO map_configs.layers(layer_id,title,type,visible,icon,queryable,info) VALUES ('osm#1','OpenStreetMap','OSM',true,'img/png/osm.png',false,'Bakgrundslager från OpenStreetMap.');

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
    info character varying COLLATE pg_catalog."default",
    tilegrid character varying COLLATE pg_catalog."default",
    CONSTRAINT map_pk PRIMARY KEY (map_id)
);

INSERT INTO map_configs.maps(map_id,footer,layers,groups,info) VALUES ('origo-cities#1','origo#1','{origo-cities#1}','{none#1,background#1}','En demokarta som visar kommuner delaktiga i Origoprojektet.');

CREATE TABLE map_configs.proj4defs
(
    code character varying COLLATE pg_catalog."default" NOT NULL,
    projection character varying COLLATE pg_catalog."default" NOT NULL,
    alias character varying COLLATE pg_catalog."default",
    CONSTRAINT proj4defs_pkey PRIMARY KEY (code)
);

INSERT INTO map_configs.proj4defs(code,projection) VALUES ('EPSG:3006','+proj=utm +zone=33 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs');

CREATE TABLE map_configs.services
(
    service_id character varying COLLATE pg_catalog."default" NOT NULL,
    base_url character varying COLLATE pg_catalog."default",
    alias character varying COLLATE pg_catalog."default",
    info character varying COLLATE pg_catalog."default",
    CONSTRAINT services_pkey PRIMARY KEY (service_id)
);

CREATE TABLE map_configs.sources
(
    source_id character varying COLLATE pg_catalog."default" NOT NULL,
    service character varying COLLATE pg_catalog."default",
    with_geometry boolean,
    fi_point_tolerance integer,
    ttl integer,
    info character varying COLLATE pg_catalog."default",
    tilegrid character varying COLLATE pg_catalog."default",
    CONSTRAINT sources_pkey PRIMARY KEY (source_id)
);

CREATE TABLE map_configs.tilegrids
(
    tilegrid_id character varying COLLATE pg_catalog."default" NOT NULL,
    alignbottomleft boolean,
    extent box,
    minzoom integer,
    resolutions numeric[],
    tilesize integer,
    CONSTRAINT tilegrids_pkey PRIMARY KEY (tilegrid_id)
);
