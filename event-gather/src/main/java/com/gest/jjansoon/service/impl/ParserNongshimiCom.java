package com.gest.jjansoon.service.impl;

import java.io.IOException;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Bean;
import org.springframework.stereotype.Service;

import com.gest.jjansoon.service.Parser;
import com.gest.jjansoon.service.dao.ContentDao;

@Service
public class ParserNongshimiCom implements Parser {
	private static final Logger logger = LoggerFactory.getLogger(ParserNongshimiCom.class);
	
	@Autowired
	private ContentDao			contentDao;
	
	@Bean
	public ParserNongshimiCom getParser() {
		return new ParserNongshimiCom();
	}
	
	@Override
	public void parse(Map<String, String> idx) throws IOException {
		
		Map<String, String> detail = contentDao.selectContentDomDetail(idx);
		Document doc = Jsoup.connect(detail.get("event_list_url")).get();
		Elements domLists = doc.select(detail.get("dom_list"));

		Iterator it = domLists.iterator();
		while (it.hasNext()) {
			Element row = (Element) it.next();
			logger.debug("dom -{}", detail);
			logger.debug("href - {}",row.attr("href"));
			
			Document subPage = null;
			try {
				subPage = Jsoup.connect(row.attr("href")).get();
			} catch (IOException e1) {
			}
			
			try {
				Elements title = subPage.select(detail.get("dom_title"));
				Elements desc = subPage.select(detail.get("dom_desc"));
//				Element url = subPage.select(dom.get("dom_url")).first();
//				Element thumb = subPage.select(item.get("dom_thumb")).first();
				Elements sdate = subPage.select(detail.get("dom_sdate"));
				Elements edate = subPage.select(detail.get("dom_edate"));
//				Elements fdate = subPage.select(item.get("dom_fdate"));
//				Elements gift_info = subPage.select(item.get("dom_gift_info"));
				
				Map e = new HashMap();
				e.put("userno", "1");
				e.put("dom_idx", detail.get("idx"));
				e.put("domain", idx.get("domain"));
				e.put("title", title.html() );
				e.put("descript", desc.html());
				e.put("landing_uri", row.attr("href"));
				e.put("landing", row.attr("href"));
//				e.put("thumb_url", String.format("http://%s%s", e.get("domain"), thumb.attr("style").substring(thumb.attr("style").indexOf("/"), thumb.attr("style").indexOf(")"))) );
//				e.put("sdate", sdate.first().html().split("-")[0].trim() );
//				e.put("edate", sdate.first().html().split("-")[1].trim() );
//				e.put("fdate", fdate.html());
//				e.put("gift_info", desc.html());
				logger.debug("e - {}", e);

				Map<String, String> chk = contentDao.selectContentfromKey(e);
				if( chk==null ) {
					contentDao.insertContent(e);
				}
			}catch(Exception e) {
				logger.error("err ",e);
			}
			
		}
		contentDao.updateContentUpdateDates(detail);
	}

}
