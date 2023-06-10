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
public class ParserAllaboutpcCoKr implements Parser {
	private static final Logger logger = LoggerFactory.getLogger(ParserAllaboutpcCoKr.class);
	
	@Autowired
	private ContentDao			contentDao;
	
	@Bean
	public ParserAllaboutpcCoKr getParser() {
		return new ParserAllaboutpcCoKr();
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
			logger.debug("row -{}", row);
			
			try {
				Elements title = row.select(detail.get("dom_title"));
				Elements desc = row.select(detail.get("dom_desc"));
				Elements url = row.select(detail.get("dom_url"));
//				Element thumb = subPage.select(item.get("dom_thumb")).first();
				Elements sdate = row.select(detail.get("dom_sdate"));
				Elements edate = row.select(detail.get("dom_edate"));
//				Elements fdate = subPage.select(item.get("dom_fdate"));
//				Elements gift_info = subPage.select(item.get("dom_gift_info"));
				
				Map e = new HashMap();
				e.put("userno", "1");
				e.put("dom_idx", detail.get("idx"));
				e.put("domain", idx.get("domain"));
				e.put("title", title.first().html() );
				e.put("descript", desc.first().html());
				e.put("landing_uri", url.first().attr("href"));
				e.put("landing", String.format("http://%s%s", e.get("domain"), e.get("landing_uri")));
//				e.put("thumb_url", String.format("http://%s%s", e.get("domain"), thumb.attr("style").substring(thumb.attr("style").indexOf("/"), thumb.attr("style").indexOf(")"))) );
//				e.put("sdate", sdate.html().split("&nbsp;~&nbsp;")[0].trim() );
//				e.put("edate", sdate.html().split("&nbsp;~&nbsp;")[1].trim() );
//				e.put("fdate", fdate.html());
//				e.put("gift_info", desc.html());
				logger.debug("e - {}", e);
				
				Map<String, String> chk = contentDao.selectContentfromKey(e);
				if( chk==null ) {
					contentDao.insertContent(e);
				}
			}catch(Exception e) {
				logger.error("err ", e);
			}
			
		}
		contentDao.updateContentUpdateDates(detail);
	}

}
