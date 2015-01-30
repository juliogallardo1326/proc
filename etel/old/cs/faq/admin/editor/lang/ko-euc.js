// I18N constants 

// LANG: "ko-euc", ENCODING: euc-kr
// by YoungJoon Kim -- yjkim@terracetech.com

HTMLArea.I18N = {

	// the following should be the filename without .js extension
	// it will be used for automatically load plugin language.
	lang: "ko-euc",

	tooltips: {
		bold:           "굵게",
		italic:         "기울임꼴",
		underline:      "밑줄",
		strikethrough:  "취소선",
		subscript:      "아래 첨자",
		superscript:    "위 첨자",
		justifyleft:    "왼쪽 맞춤",
		justifycenter:  "가운데 맞춤",
		justifyright:   "오른쪽 맞춤",
		justifyfull:    "균등 분할",
		orderedlist:    "번호 매기기",
		unorderedlist:  "글머리 기호",
		outdent:        "내어쓰기",
		indent:         "들여쓰기",
		forecolor:      "글자색",
		backcolor:      "배경색",
		horizontalrule: "수평선 삽입",
		createlink:     "링크작성",
		insertimage:    "그림 삽입/수정",
		inserttable:    "표 삽입",
		htmlmode:       "HTML표시변환",
		popupeditor:    "편집창 확대",
		about:          "편집기 정보",
	    showhelp:       "도움말 보이기",
		textindicator:  "현재 스타일",
		undo:           "입력 취소",
		redo:           "입력 다시 실행",
		cut:            "잘라내기",
		copy:           "복사",
		paste:          "붙이기",
		lefttoright:    "왼쪽에서 오른쪽으로 쓰기",
		righttoleft:    "오른쪽에서 왼쪽으로 쓰기"
	}
};


	buttons: {
		"ok":           "예",
		"cancel":       "취소"
	},

	msg: {
		"Path":         "경로",
		"TEXT_MODE":    "텍스트 모드입니다. HTML 모드로 돌아가시려면 [<>] 를 클릭하세요.",

		"IE-sucks-full-screen" :
		// translate here
		"I.E.(인터넷 익스플로러)에서는 전체 창 모드에서 문제가 있습니다. " +
		"브라우저 문제이기 때문에 어쩔수가 없습니다.  " +
		"윈도우 9x를 사용하고 계시는 경우, " +
		" 'General Protection Fault' 와 같은 에러가 발생하며, 다시 시작(reboot) 하실 필요가 있습니다.\n\n" +
		"전체 창 모드를 사용하고 싶은 경우에는 '예'를 클릭하십시오.",

		"Moz-Clipboard" :
		"권한이 없는 스크립트인 경우, 보안상의 이유로 잘라내기/복사/붙이기를 사용할 수 없습니다." +
		" mozilla.org에서 해당 원인을 해결하는 방법을 확인하실 수 있습니다. " +
		" 기술 정보를 보시려면 '예'를 클릭하세요. "
	},

	dialogs: {
		"Cancel"                                            : "취소",
		"Insert/Modify Link"                                : "링크 삽입/수정",
		"New window (_blank)"                               : "새 창(_blank)",
		"None (use implicit)"                               : "없음 (use implicit)",
		"OK"                                                : "예",
		"Other"                                             : "다른",
		"Same frame (_self)"                                : "같은 프레임 (_self)",
		"Target:"                                           : "타겟:",
		"Title (tooltip):"                                  : "제목 (툴팁):",
		"Top frame (_top)"                                  : "탑 프레임 (_top)",
		"URL:"                                              : "URL:",
		"You must enter the URL where this link points to"  : "링크가 가리키는 URL을 반드시 입력하세요."
	}
};
