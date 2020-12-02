# Secure and Minimal image of Postgres
# https://hub.docker.com/repository/docker/huggla/sam-postgres

# =========================================================================
# Init
# =========================================================================
# ARGs (can be passed to Build/Final) <BEGIN>
ARG SaM_VERSION="2.0.4"
ARG IMAGETYPE="application"
ARG CONTENTIMAGE1="node:alpine"
ARG CONTENTDESTINATION1="/"
ARG BASEIMAGE="huggla/sam-lighttpd2:20201125"
ARG CLONEGITS="https://github.com/origo-map/origo.git"
ARG BUILDDEPS="python2"
ARG BUILDCMDS=\
"   cd origo "\
"&& npm install "\
"&& npm --depth 8 update "\
"&& npm run prebuild-sass "\
"&& npm run build "\
"&& sed -i 's/origo.js/origo.min.js/' build/index.html "\
"&& find /finalfs ! -path \"/finalfs/start*\" -delete "\
"&& ls -la /finalfs "\
"&& cp -a build /finalfs/origo"
# ARGs (can be passed to Build/Final) </END>

# Generic template (don't edit) <BEGIN>
FROM ${CONTENTIMAGE1:-scratch} as content1
FROM ${CONTENTIMAGE2:-scratch} as content2
FROM ${CONTENTIMAGE3:-scratch} as content3
FROM ${CONTENTIMAGE4:-scratch} as content4
FROM ${CONTENTIMAGE5:-scratch} as content5
FROM ${INITIMAGE:-${BASEIMAGE:-huggla/secure_and_minimal:$SaM_VERSION-base}} as init
# Generic template (don't edit) </END>

RUN exec > /build.log 2>&1 \	
 && set -ex +fam \	
 && mkdir /environment /tmp/onbuild \	
 && (find . -type l ! -path './tmp/*' ! -path './var/cache/*' ! -path './proc/*' ! -path './sys/*' ! -path './dev/*' -exec sh -c 'echo -n "$(echo "{}" | cut -c 2-)>"' \; -exec readlink "{}" \; && find . -type f ! -path './tmp/*' ! -path './var/cache/*' ! -path './proc/*' ! -path './sys/*' ! -path './dev/*' -exec md5sum "{}" \; | awk '{first=$1; $1=""; print $0">"first}' | sed 's|^ [.]||') | sort -u - > /tmp/onbuild/exclude.filelist \	
 && tar -c -z -f /environment/onbuild.tar.gz -C /tmp onbuild

# =========================================================================
# Build
# =========================================================================
# Generic template (don't edit) <BEGIN>
FROM ${BUILDIMAGE:-huggla/secure_and_minimal:$SaM_VERSION-build} as build
FROM ${BASEIMAGE:-huggla/secure_and_minimal:$SaM_VERSION-base} as final
COPY --from=build /finalfs /
# Generic template (don't edit) </END>

# =========================================================================
# Final
# =========================================================================
ENV VAR_ORIGO_CONFIG_DIR="/etc/origo" \
    VAR_OPERATION_MODE="normal" \
    VAR_setup1_module_load="[ 'mod_deflate' ]" \
    VAR_WWW_DIR="/origo"

# Generic template (don't edit) <BEGIN>
USER starter
ONBUILD USER root
# Generic template (don't edit) </END>
