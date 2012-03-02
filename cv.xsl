<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:zapi="http://zotero.org/ns/api"version='1.0'>

	<xsl:output method="html"/>

	<xsl:template match="/">

		<div id="cv">

		<xsl:for-each select="zapi:cvsection">

			<div class="cvSection">
			
				<div class="cvSectionTitle"><xsl:value-of select="./@title"/></div>
			
					<div class="cvSectionBody"><xsl:copy-of select="."/></div>
			
			</div>
			
		</xsl:for-each>

		</div>

    </xsl:template>   

</xsl:stylesheet>