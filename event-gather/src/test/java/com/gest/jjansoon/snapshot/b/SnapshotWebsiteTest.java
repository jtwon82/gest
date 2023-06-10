package com.gest.jjansoon.snapshot.b;
//package com.gest.jjansoon;
//
//import java.io.File;
//import java.io.IOException;
//
//import org.apache.commons.io.FileUtils;
//import org.openqa.selenium.OutputType;
//import org.openqa.selenium.TakesScreenshot;
//import org.openqa.selenium.WebDriver;
//import org.openqa.selenium.firefox.FirefoxDriver;
//
//
//public class SnapshotWebsiteTest {
//
//	public static void main(String[] args) {
//		String link = "http://jjansoon.co.kr/";
//		File screenShot = new File("d:\\screenshot.png").getAbsoluteFile();
//
//		WebDriver driver = new FirefoxDriver();
//		System.out.println( driver );
//		try {
//			driver.get(link);
//
//			try {
//				Thread.sleep(5);
//			} catch (InterruptedException e1) {
//			}
//
//			final File outputFile = ((TakesScreenshot) driver).getScreenshotAs(OutputType.FILE);
//			try {
//				FileUtils.copyFile(outputFile, screenShot);
//			} catch (IOException e) {
//			}
//		}catch(Exception e) {
//			System.out.println( e );
//		} finally {
//			driver.close();
//		}
//	}
//
//}
