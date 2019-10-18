# =========================================================================
# Init
# =========================================================================
# ARGs (can be passed to Build/Final) <BEGIN>
ARG SaM_VERSION="1.0"
ARG TAG="20191018"
ARG IMAGETYPE="application"
ARG BASEIMAGE="node:alpine"
ARG CLONEGITS="https://github.com/SigtunaGIS/origo"
ARG BUILDCMDS=\
"   cd origo "\
"&& npm install "\
"&& cp -a /origo /finalfs/"
# ARGs (can be passed to Build/Final) </END>

# Generic template (don't edit) <BEGIN>
FROM ${CONTENTIMAGE1:-scratch} as content1
FROM ${CONTENTIMAGE2:-scratch} as content2
FROM ${CONTENTIMAGE3:-scratch} as content3
FROM ${CONTENTIMAGE4:-scratch} as content4
FROM ${CONTENTIMAGE5:-scratch} as content5
FROM ${INITIMAGE:-${BASEIMAGE:-huggla/base:$SaM_VERSION-$TAG}} as init
# Generic template (don't edit) </END>

RUN mkdir /environment /tmp/onbuild \
 && (find . -type l ! -path './tmp/*' ! -path './var/cache/*' ! -path './proc/*' ! -path './sys/*' ! -path './dev/*' -exec sh -c 'echo -n "$(echo "{}" | cut -c 2-)>"' \; -exec readlink "{}" \; && find . -type f ! -path './tmp/*' ! -path './var/cache/*' ! -path './proc/*' ! -path './sys/*' ! -path './dev/*' -exec md5sum "{}" \; | awk '{first=$1; $1=""; print $0">"first}' | sed 's|^ [.]||') | sort -u - > /tmp/onbuild/exclude.filelist.new \
 && tar -c -z -f /finalfs/environment/onbuild.tar.gz -C /tmp onbuild

# =========================================================================
# Build
# =========================================================================
# Generic template (don't edit) <BEGIN>
FROM ${BUILDIMAGE:-huggla/build:$SaM_VERSION-$TAG} as build
FROM ${BASEIMAGE:-huggla/base:$SaM_VERSION-$TAG} as final
COPY --from=build /finalfs /
# Generic template (don't edit) </END>

# =========================================================================
# Final
# =========================================================================
ENV VAR_FINAL_COMMAND="cd origo && npm start"

# Generic template (don't edit) <BEGIN>
USER starter
ONBUILD USER root
# Generic template (don't edit) </END>
