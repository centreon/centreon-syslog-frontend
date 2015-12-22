<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template match="/">
		<xsl:for-each select="//root">
			<xsl:element name='table'>
				<xsl:attribute name='class'>ajaxOption table</xsl:attribute>
				<th><h5>Filters</h5></th>
				<xsl:element name='tr'>
					<xsl:for-each select="filters">
						<xsl:for-each select="filter">
							<td>
								<h4><xsl:value-of select="."/></h4>
							</td>
						</xsl:for-each>
					</xsl:for-each>
				</xsl:element>
				<xsl:element name='tr'>
					<xsl:for-each select="hosts">
						<td>
							<xsl:element name='select'>
								<xsl:attribute name='id'>filter_host</xsl:attribute>
								<xsl:attribute name='onChange'>build_ajax();</xsl:attribute>
								<xsl:attribute name='name'>filter_host</xsl:attribute>
								<xsl:for-each select="host">
									<xsl:variable name="tmp"><xsl:value-of select="@selected"/></xsl:variable>
									<xsl:element name="option">
										<xsl:attribute name='value'><xsl:value-of select="."/></xsl:attribute>
										<xsl:choose>
											<xsl:when test="@selected='Y'">
												<xsl:attribute name="SELECTED"></xsl:attribute>
											</xsl:when>
										</xsl:choose>
										<xsl:value-of select="."/>
									</xsl:element>
								</xsl:for-each>
							</xsl:element>
						</td>
					</xsl:for-each>
					<xsl:for-each select="facilities">
						<td>
							<xsl:element name='select'>
								<xsl:attribute name='id'>filter_Ffacility</xsl:attribute>
								<xsl:attribute name='onChange'>build_ajax();</xsl:attribute>
								<xsl:attribute name='name'>filter_Ffacility</xsl:attribute>
								<xsl:for-each select="Ffacility">
									<xsl:variable name="tmp"><xsl:value-of select="@selected"/></xsl:variable>
									<xsl:element name="option">
										<xsl:attribute name='value'><xsl:value-of select="."/></xsl:attribute>
										<xsl:choose>
											<xsl:when test="@selected='Y'">
												<xsl:attribute name="SELECTED"></xsl:attribute>
											</xsl:when>
										</xsl:choose>
										<xsl:value-of select="."/>
									</xsl:element>
								</xsl:for-each>
							</xsl:element>
							<xsl:element name='select'>
								<xsl:attribute name='id'>filter_facility</xsl:attribute>
								<xsl:attribute name='onChange'>build_ajax();</xsl:attribute>
								<xsl:attribute name='name'>filter_facility</xsl:attribute>
								<xsl:for-each select="facility">
									<xsl:variable name="tmp"><xsl:value-of select="@selected"/></xsl:variable>
									<xsl:element name="option">
										<xsl:attribute name='value'><xsl:value-of select="."/></xsl:attribute>
										<xsl:choose>
											<xsl:when test="@selected='Y'">
												<xsl:attribute name="SELECTED"></xsl:attribute>
											</xsl:when>
										</xsl:choose>
										<xsl:value-of select="."/>
									</xsl:element>
								</xsl:for-each>
							</xsl:element>
						</td>
					</xsl:for-each>
					<xsl:for-each select="severities">
						<td>
							<xsl:element name='select'>
								<xsl:attribute name='id'>filter_Fseverity</xsl:attribute>
								<xsl:attribute name='onChange'>build_ajax();</xsl:attribute>
								<xsl:attribute name='name'>filter_Fseverity</xsl:attribute>
								<xsl:for-each select="Fseverity">
									<xsl:variable name="tmp"><xsl:value-of select="@selected"/></xsl:variable>
									<xsl:element name="option">
										<xsl:attribute name='value'><xsl:value-of select="."/></xsl:attribute>
										<xsl:choose>
											<xsl:when test="@selected='Y'">
												<xsl:attribute name="SELECTED"></xsl:attribute>
											</xsl:when>
										</xsl:choose>
										<xsl:value-of select="."/>
									</xsl:element>
								</xsl:for-each>
							</xsl:element>
							<xsl:element name='select'>
								<xsl:attribute name='id'>filter_severity</xsl:attribute>
								<xsl:attribute name='onChange'>build_ajax();</xsl:attribute>
								<xsl:attribute name='name'>filter_severity</xsl:attribute>
								<xsl:for-each select="severity">
									<xsl:variable name="tmp"><xsl:value-of select="@selected"/></xsl:variable>
									<xsl:element name="option">
										<xsl:attribute name='value'><xsl:value-of select="."/></xsl:attribute>
										<xsl:choose>
											<xsl:when test="@selected='Y'">
												<xsl:attribute name="SELECTED"></xsl:attribute>
											</xsl:when>
										</xsl:choose>
										<xsl:value-of select="."/>
									</xsl:element>
								</xsl:for-each>
							</xsl:element>
						</td>
					</xsl:for-each>
					<xsl:for-each select="programs">
						<td>
							<xsl:element name='select'>
								<xsl:attribute name='id'>filter_program</xsl:attribute>
								<xsl:attribute name='onChange'>build_ajax();</xsl:attribute>
								<xsl:attribute name='name'>filter_program</xsl:attribute>
								<xsl:for-each select="program">
									<xsl:variable name="tmp"><xsl:value-of select="@selected"/></xsl:variable>
									<xsl:element name="option">
										<xsl:attribute name='value'><xsl:value-of select="."/></xsl:attribute>
										<xsl:choose>
											<xsl:when test="@selected='Y'">
												<xsl:attribute name="SELECTED"></xsl:attribute>
											</xsl:when>
										</xsl:choose>
										<xsl:value-of select="."/>
									</xsl:element>
								</xsl:for-each>
							</xsl:element>
						 </td>
					</xsl:for-each>
					<xsl:for-each select="msg">
						<td>
							<input type="text">
								<xsl:attribute name='id'>filter_msg</xsl:attribute>
								<xsl:attribute name='onChange'>build_ajax();</xsl:attribute>
								<xsl:attribute name='name'>filter_msg</xsl:attribute>
								<xsl:attribute name='size'>50</xsl:attribute>
							</input>
						</td>
					</xsl:for-each>
				</xsl:element>
				<xsl:element name="tr">
					<xsl:for-each select="//error">
						<xsl:element name="td">
							<xsl:attribute name='align'>center</xsl:attribute>
							<b><h4><xsl:value-of select="." /></h4></b>
						</xsl:element>
					</xsl:for-each>
				</xsl:element>
			</xsl:element>
		</xsl:for-each>
	</xsl:template>
</xsl:stylesheet>
