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
public class ParserFunJrNaverCom implements Parser {
	private static final Logger logger = LoggerFactory.getLogger(ParserFunJrNaverCom.class);

	@Autowired 
	ContentDao contentDao;
	
	@Bean
	public ParserFunJrNaverCom getParser() {
		return new ParserFunJrNaverCom();
	}
	
	@Override
	public void parse(Map<String, String> idx) throws IOException {
		try {
			Map<String, String> detail = contentDao.selectContentDomDetail(idx);
			logger.debug("detail - {}", detail);
			
			Document doc = Jsoup.connect(detail.get("event_list_url")).get();
			Elements domLists = doc.select(detail.get("dom_list"));
			
			Iterator it = domLists.iterator();
			while (it.hasNext()) {
				Element row = (Element) it.next();
				logger.debug("row -{}", row);
				
				Element title = row.select(detail.get("dom_title")).first();
				Element desc = row.select(detail.get("dom_desc")).first();
				Element url = row.select(detail.get("dom_url")).first();
				Element thumb = row.select(detail.get("dom_thumb")).first();
				Elements sdate = row.select(detail.get("dom_sdate"));
				Elements edate = row.select(detail.get("dom_edate"));
				Elements fdate = row.select(detail.get("dom_fdate"));
				Elements gift_info = row.select(detail.get("dom_gift_info"));
				
				Map e = new HashMap();
				e.put("userno", "1");
				e.put("dom_idx", detail.get("idx"));
				e.put("domain", idx.get("domain"));
				e.put("title", title.html());
				e.put("descript", desc.html());
				e.put("landing_uri", url.attr("href"));
				e.put("landing", String.format("http://%s%s", e.get("domain"), e.get("landing_uri")));
				e.put("thumb_url", thumb.attr("src"));
//				e.put("sdate", sdate.html());
//				e.put("edate", edate.html());
//				e.put("fdate", fdate.html());
				e.put("gift_info", gift_info.html());
				logger.info("event_item-{}", e);
				
				Map<String, String> chk = contentDao.selectContentfromKey(e);
				if( chk==null ) {
					contentDao.insertContent(e);
				}
			}
			contentDao.updateContentUpdateDates(detail);
		}catch(Exception e) {
			logger.error("err ", e);
		}
	}
	
}
