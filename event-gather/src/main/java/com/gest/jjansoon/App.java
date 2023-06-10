package com.gest.jjansoon;


import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.builder.SpringApplicationBuilder;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.scheduling.annotation.EnableScheduling;

@SpringBootApplication
//@ImportResource({ "classpath:batch-config.xml", "classpath:bean-config.xml" })
@ComponentScan(basePackages = "com.gest")
@EnableScheduling

public class App /*implements CommandLineRunner*/{
	private static final Logger logger = LoggerFactory.getLogger(App.class);
	
	public static void main(String[] args) {
//		System.setProperty("spring.profiles.default", "local");
		new SpringApplicationBuilder().sources(App.class).run(args);
	}

//	@Override
//	public void run(String... args) throws Exception {
//		// TODO Auto-generated method stub
//		logger.info("start");
//	}
}
