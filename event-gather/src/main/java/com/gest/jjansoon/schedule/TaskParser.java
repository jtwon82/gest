package com.gest.jjansoon.schedule;

import java.awt.Dimension;
import java.awt.Graphics2D;
import java.awt.image.BufferedImage;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.net.URL;
import java.util.Base64;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import javax.imageio.ImageIO;

import org.jsoup.Connection;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.stereotype.Component;

import com.gest.jjansoon.model.reviewAttachesVo;
import com.gest.jjansoon.model.smartstoreContentsVo;
import com.gest.jjansoon.model.smartstoreRootVo;
import com.gest.jjansoon.service.dao.ContentDao;
import com.gest.jjansoon.service.impl.ParserAllaboutpcCoKr;
import com.gest.jjansoon.service.impl.ParserChungjungoneCom;
import com.gest.jjansoon.service.impl.ParserEventUmmawaCom;
import com.gest.jjansoon.service.impl.ParserFunJrNaverCom;
import com.gest.jjansoon.service.impl.ParserKwaterOrKr;
import com.gest.jjansoon.service.impl.ParserLghausysCoKr;
import com.gest.jjansoon.service.impl.ParserMallHyundailivartCoKr;
import com.gest.jjansoon.service.impl.ParserNongshimiCom;
import com.gest.jjansoon.service.impl.ParserRewviewCoKr;
import com.gest.jjansoon.service.impl.ParserS20CoKr;
import com.gest.jjansoon.service.impl.ParserSamsungsalesCoKr;
import com.gest.jjansoon.service.impl.ParserSeoulmilkCoKr;
import com.gest.jjansoon.service.impl.ParserTaplayCoKr;
import com.gest.util.TimeToLiveCollection;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

@Component
public class TaskParser {

	private static final Logger		logger		= LoggerFactory.getLogger(TaskParser.class);

	private static TimeToLiveCollection	workingKey	= new TimeToLiveCollection();
	
	@Autowired
	private ContentDao			contentDao;

	@Autowired
	ParserFunJrNaverCom parserFunJrNaverCom;
	@Autowired
	ParserChungjungoneCom parserChungjungoneCom;
	@Autowired
	ParserSeoulmilkCoKr parserSeoulmilkCoKr;
	@Autowired
	ParserNongshimiCom parserNongshimiCom;
	@Autowired
	ParserKwaterOrKr parserKwaterOrKr;
	@Autowired
	ParserSamsungsalesCoKr parserSamsungsalesCoKr;
	@Autowired
	ParserRewviewCoKr ParserRewviewCoKr;
	@Autowired
	ParserMallHyundailivartCoKr parserMallHyundailivartCoKr;
	@Autowired
	ParserLghausysCoKr parserLghausysCoKr;
	@Autowired
	ParserAllaboutpcCoKr parserAllaboutpcCoKr;
	@Autowired
	ParserEventUmmawaCom parserEventUmmawaCom;
	@Autowired
	ParserS20CoKr parserS20CoKr;
	@Autowired
	ParserTaplayCoKr parserTaplayCoKr;
	
//	@Scheduled(fixedDelay = 5000)
	public void sec1() {
		logger.info("5sec");
		Map<String, String> list = contentDao.selectNow();
		
		System.out.println(list);
	}
	public Dimension getScaledDimension(Dimension imgSize, Dimension boundary) {

	    int original_width = imgSize.width;
	    int original_height = imgSize.height;
	    int bound_width = boundary.width;
	    int bound_height = boundary.height;
	    int new_width = original_width;
	    int new_height = original_height;

	    // first check if we need to scale width
	    if (original_width > bound_width) {
	        //scale width to fit
	        new_width = bound_width;
	        //scale height to maintain aspect ratio
	        new_height = (new_width * original_height) / original_width;
	    }

	    // then check if we need to scale even with the new height
	    if (new_height > bound_height) {
	        //scale height to fit instead
	        new_height = bound_height;
	        //scale width to maintain aspect ratio
	        new_width = (new_height * original_width) / original_height;
	    }

	    return new Dimension(new_width, new_height);
	}

	public  BufferedImage resize(URL url, Dimension boundary) throws IOException{
	    final BufferedImage image= ImageIO.read(url);
	    
	    Dimension size= getScaledDimension(new Dimension(image.getWidth(), image.getHeight()), boundary);
	    
	    final BufferedImage resized = new BufferedImage(size.width, size.height, BufferedImage.TYPE_INT_RGB);
	    final Graphics2D g = resized.createGraphics();
	    g.drawImage(image, 0, 0, size.width, size.height, null);
	    g.dispose();
	    return resized;
	}
	public String encodeToString(BufferedImage image, String type) {
	    String imageString = null;
	    ByteArrayOutputStream bos = new ByteArrayOutputStream();

	    try {
	        ImageIO.write(image, type, bos);
	        byte[] imageBytes = bos.toByteArray();

	        Base64.Encoder encoder = Base64.getEncoder();
	        imageString = encoder.encodeToString(imageBytes);

	        bos.close();
	    } catch (IOException e) {
	        e.printStackTrace();
	    }
	    return imageString;
	}
	public String makeImageToBase64(String url, int width, int height) {
		String base64Str="";
		try {
			BufferedImage image= resize(new URL(url), new Dimension(200, 200));
			base64Str= encodeToString(image, "jpg");
		}catch(Exception e) {
			logger.error("err ",e);
		}
		return base64Str;
	}

	@Scheduled(fixedDelay = 5000)
	public void buildShopReview()  {
		Gson gson = new Gson();
		Map<String, String> map= contentDao.selectBoardReview();
		
		if(map!=null) {
			try {
				String naverUrl= map.get("etc1").toString();
				int naverMaxPage=1;// Integer.parseInt(map.get("etc2").toString());
				String naverEtc3= map.get("etc3").toString();
				
				if( !"succ".equals(naverEtc3) ) {
					for( int currPage=1; currPage <= naverMaxPage; currPage++ ) {
						String currUrl = naverUrl + currPage;
						logger.info("currUrl {}", currUrl);
						
						String response= Jsoup.connect( currUrl ).ignoreContentType(true).execute().body();
						try {
							JsonObject root= new JsonParser().parse(response).getAsJsonObject();
							smartstoreRootVo vo= gson.fromJson(root, smartstoreRootVo.class);
							for(smartstoreContentsVo vo2 : vo.getContents()) {
								List<reviewAttachesVo> imgList= vo2.getReviewAttaches();
								
								StringBuffer imgStr_origin= new StringBuffer();
								StringBuffer imgStr= new StringBuffer();
								int img_cnt=0;
								for(reviewAttachesVo img : imgList) {
									String base64= makeImageToBase64(img.getAttachPath(), 400, 400);
									
									imgStr_origin.append("<img src='"+img.getAttachPath()+"'>");
									imgStr.append("<img src=\"data:image/jpg;base64,"+ base64 +"\" >");
									if(img_cnt++>4)break;
								}
								
								Map param= new HashMap();
								param.put("b_idx", map.get("idx"));
								param.put("key_id", vo2.getId());
								param.put("mall_name", "naver");
								param.put("images_origin", imgStr_origin.toString());
								param.put("images", imgStr.toString());
								param.put("content", vo2.getReviewContent());
								param.put("write_date", vo2.getCreateDate().substring(0,10));
								logger.info("b_idx:{}, mall_name:{}, key_id id:{}", param.get("b_idx"), param.get("mall_name"), param.get("key_id"));
								try {
									contentDao.insertBoardReview(param);
								}catch(Exception e) {}
							}
						}catch(Exception e) {}
					}
					Map param= new HashMap();
					param.put("idx", map.get("idx"));
					param.put("etc3", "succ");
					contentDao.updateBoardReviewSucc(param);
				}
			} catch(Exception e) {
				logger.info("err ", e);
			}

			try {
				String coupangUrl= map.get("etc4").toString();
				int coupangMaxPage=1;// Integer.parseInt(map.get("etc5").toString());
				String coupangEtc6= map.get("etc6").toString();
				
				if( !"succ".equals(coupangEtc6) ) {
					for( int currPage=1; currPage <= coupangMaxPage; currPage++ ) {
						String currUrl = coupangUrl + currPage;
						logger.info("currUrl {}", currUrl);
						
						Document response= Jsoup.connect( currUrl )
								.header("accept","text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9")
								.header("accept-encoding","gzip, deflate, br")
								.header("accept-language","ko-KR,ko;q=0.9,en-US;q=0.8,en;q=0.7")
								.header("cache-control","no-cache")
								.header("pragma","no-cache")
								.header("sec-ch-ua-mobile","?0")
								.header("sec-fetch-dest","document")
								.header("sec-fetch-mode","navigate")
								.header("sec-fetch-site","none")
								.header("sec-fetch-user","?1")
								.header("upgrade-insecure-requests","1")
								.header("user-agent","Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36")
                                .method(Connection.Method.GET).get();

						Elements list= response.select(".sdp-review__article__list.js_reviewArticleReviewList");
						Iterator it = list.iterator();
						while (it.hasNext()) {
							Element row = (Element) it.next();
							
							Elements uname= row.select(".sdp-review__article__list__info__user__name");
							String date= row.select(".sdp-review__article__list__info__product-info__reg-date").html();
							String starNum = row.select(".sdp-review__article__list__info__product-info__star-orange").attr("data-rating");
							Elements imageList= row.select(".sdp-review__article__list__attachment .sdp-review__article__list__attachment__img");
							String content= row.select(".sdp-review__article__list__review__content").html();
							
							StringBuffer imgStr_origin= new StringBuffer();
							StringBuffer imgStr= new StringBuffer();
							int img_cnt=0;
							Iterator it2= imageList.iterator();
							while (it2.hasNext()) {
								Element img= (Element) it2.next();
								String base64= makeImageToBase64(img.attr("src"), 400, 400);
								imgStr_origin.append("<img src='"+img.attr("src")+"'>");
								imgStr.append("<li><img src=\"data:image/jpg;base64,"+ base64 +"\" ></li>");
								if(img_cnt++>4)break;
							}
							
							Map param= new HashMap();
							param.put("b_idx", map.get("idx"));
							param.put("key_id", uname.attr("data-member-id"));
							param.put("mall_name", "coupang");
							param.put("star_num", starNum);
							param.put("images_origin", imgStr_origin.toString());
							param.put("images", imgStr.toString());
							param.put("content", content);
							param.put("write_date", date.substring(0,10));
							logger.info("b_idx:{}, mall_name:{}, key_id id:{}", param.get("b_idx"), param.get("mall_name"), param.get("key_id"));
							try {
								contentDao.insertBoardReview(param);
							}catch(Exception e) {
								logger.error("err ",e);
							}
						}
					}
					Map param= new HashMap();
					param.put("idx", map.get("idx"));
					param.put("etc6", "succ");
//					contentDao.updateBoardReviewSucc(param);
				}

			}catch(Exception e) {
				logger.error("err ", e);
			}
		}
	}

//	@Scheduled(cron = "0 0 12 * * *")
	public void test() {
		logger.debug("START test");
		
		List<Map> list = contentDao.selectContentDom();
		
		for( Map<String,String> item : list ) {
			try {
				String [] list_url = item.get("event_list_url").split("/");
				item.put("domain", list_url[2]);
				logger.info("update_dates - {}", item.get("update_dates"));
				logger.debug("domain - {}", item.get("domain"));
				logger.debug("idx - {}", item.get("idx"));
				
				if( item.get("domain").indexOf("fun.jr.naver.com")>-1 ) {
					parserFunJrNaverCom.parse(item);
				}
				else if ( item.get("domain").indexOf("chungjungone.com")>-1 ) {
					parserChungjungoneCom.parse(item);
				}
				else if ( item.get("domain").indexOf("seoulmilk.co.kr")>-1 ) {
					parserSeoulmilkCoKr.parse(item);
				}
				else if ( item.get("domain").indexOf("nongshimi.com")>-1 ) {
					parserNongshimiCom.parse(item);
				}
				else if ( item.get("domain").indexOf("kwater.or.kr")>-1 ) {
					parserKwaterOrKr.parse(item);
				}
				else if ( item.get("domain").indexOf("www.samsungsales.co.kr")>-1 ) {
					parserSamsungsalesCoKr.parse(item);
				}
				else if ( item.get("domain").indexOf("rewview.co.kr")>-1 ) {
					ParserRewviewCoKr.parse(item);
				}
				else if ( item.get("domain").indexOf("mall.hyundailivart.co.kr")>-1 ) {
					parserMallHyundailivartCoKr.parse(item);
				}
				else if ( item.get("domain").indexOf("www.lghausys.co.kr")>-1 ) {
					parserLghausysCoKr.parse(item);
				}
				else if ( item.get("domain").indexOf("www.allaboutpc.co.kr")>-1 ) {
					parserAllaboutpcCoKr.parse(item);
				}
				else if ( item.get("domain").indexOf("event.ummawa.com")>-1) {
					parserEventUmmawaCom.parse(item);
				}
				else if ( item.get("domain").indexOf("s20.co.kr")>-1) {
					parserS20CoKr.parse(item);
				}
				else if ( item.get("domain").indexOf("taplay.co.kr")>-1) {
					parserTaplayCoKr.parse(item);
				}
			}catch(Exception e) {
				logger.error("TaskParser err", e);
			}
		}
	}
}
