<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:template match="/">
	<xsl:for-each select="//root">
	<table class="ListTable">
		<tr class='ListHeader'>
			<td class="SyslogListColCenterNoWrap">
				<xsl:value-of select="label_datetime"/>
			</td>
			<td class="SyslogListColCenterNoWrap">
				<xsl:value-of select="label_host"/>
			</td>
			<td class="SyslogListColCenterNoWrap">
				<xsl:value-of select="label_facility"/>
			</td>
			<td class="SyslogListColCenterNoWrap">
				<xsl:value-of select="label_severity"/>
			</td>	
			<td class="SyslogListColCenterNoWrap">
				<xsl:value-of select="label_program"/>
			</td>
			<td class="SyslogListColCenterNoWrap">
				<xsl:value-of select="label_msg"/>
			</td>
		</tr>		
		<xsl:for-each select="//syslog">
			<xsl:element name="tr">
				<xsl:attribute name="class"><xsl:value-of select="style" /></xsl:attribute>			 			
				<td class="SyslogListColCenterNoWrap">
					<xsl:value-of select="datetime"/>
				</td>
				<td class="SyslogListColCenterNoWrap">
					<xsl:value-of select="host"/>
				</td>
				<td class="SyslogListColCenterNoWrap">
					<xsl:value-of select="facility"/>
				</td>			
				<xsl:element name="td">
					<xsl:attribute name="align">center</xsl:attribute>
					<xsl:element name="span">
						<xsl:attribute name="class">
							<xsl:value-of select="prio_class" />
						</xsl:attribute>
						<xsl:value-of select="severity"/>
					</xsl:element>
				</xsl:element>
				<td class="SyslogListColCenterNoWrap">
					<xsl:value-of select="program"/>
				</td>
				<td>
					<xsl:value-of select="msg"/>
				</td>
			</xsl:element>
		</xsl:for-each>
		<xsl:for-each select="//error">
			<xsl:element name="tr">
				<xsl:for-each select="//msg">
					<xsl:value-of select="." />
				</xsl:for-each>
			</xsl:element>
		</xsl:for-each>
	</table>		
	</xsl:for-each>
</xsl:template>
</xsl:stylesheet>
