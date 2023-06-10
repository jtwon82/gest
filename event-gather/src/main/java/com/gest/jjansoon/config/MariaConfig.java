package com.gest.jjansoon.config;

import javax.sql.DataSource;

import org.apache.ibatis.session.ExecutorType;
import org.apache.ibatis.session.SqlSessionFactory;
import org.apache.ibatis.type.TypeHandlerRegistry;
import org.mybatis.spring.SqlSessionFactoryBean;
import org.mybatis.spring.SqlSessionTemplate;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.boot.autoconfigure.jdbc.DataSourceBuilder;
import org.springframework.boot.context.properties.ConfigurationProperties;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.Primary;
import org.springframework.context.annotation.PropertySource;
import org.springframework.core.io.DefaultResourceLoader;
import org.springframework.core.io.support.PathMatchingResourcePatternResolver;
import org.springframework.transaction.annotation.EnableTransactionManagement;

@Configuration
@PropertySource("classpath:hikari.properties")
@EnableTransactionManagement
public class MariaConfig {
	
	private static final Logger logger = LoggerFactory.getLogger(MariaConfig.class);

	@Bean (destroyMethod = "close")
	@ConfigurationProperties(prefix = "spring.datasource.gestdb")
	@Primary
	public DataSource dataSourcegestdb() {
		DataSource result = DataSourceBuilder.create().build();
		return result;
	}
	
	@Bean(name="sqlSessionFactorygestdb")
	@Primary
	public SqlSessionFactory sqlSessionFactorygestdb() throws Exception {
		SqlSessionFactoryBean sqlSessionFactory = new SqlSessionFactoryBean();
		sqlSessionFactory.setDataSource(dataSourcegestdb());
		PathMatchingResourcePatternResolver resourcePatternResolver = new PathMatchingResourcePatternResolver();
		DefaultResourceLoader defaultResourceLoader = new DefaultResourceLoader();
		sqlSessionFactory.setConfigLocation(defaultResourceLoader.getResource("classpath:mybatis/mybatis-config.xml"));
		sqlSessionFactory.setMapperLocations(resourcePatternResolver.getResources("classpath:mapper/*.xml"));

		// default TypeHandler 등록.
		TypeHandlerRegistry typeHandlerRegistry = sqlSessionFactory.getObject().getConfiguration().getTypeHandlerRegistry();
		typeHandlerRegistry.register(java.sql.Timestamp.class, org.apache.ibatis.type.DateTypeHandler.class);
		typeHandlerRegistry.register(java.sql.Time.class, org.apache.ibatis.type.DateTypeHandler.class);
		typeHandlerRegistry.register(java.sql.Date.class, org.apache.ibatis.type.DateTypeHandler.class);

		return sqlSessionFactory.getObject();
	}
	
	@Bean(name = "sqlSessionTemplategestdb", destroyMethod = "clearCache")
	@Primary
	public SqlSessionTemplate sqlSessionTemplategestdb() throws Exception {
		return new SqlSessionTemplate(sqlSessionFactorygestdb(), ExecutorType.BATCH);
	}
}
